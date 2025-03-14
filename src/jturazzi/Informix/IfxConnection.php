<?php

namespace jturazzi\Informix;

use DateTimeInterface;
use Illuminate\Database\Connection;
use jturazzi\Informix\Query\Grammars\IfxGrammar as QueryGrammar;
use jturazzi\Informix\Query\Processors\IfxProcessor;
use jturazzi\Informix\Schema\Grammars\IfxGrammar as SchemaGrammar;
use jturazzi\Informix\Schema\IfxBuilder as SchemaBuilder;
use Doctrine\DBAL\Driver\PDOInformix\Driver as DoctrineDriver;

class IfxConnection extends Connection
{
    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new SchemaBuilder($this);
    }

    public function prepareBindings(array $bindings)
    {
        $grammar = $this->getQueryGrammar();
        if ($this->isTransEncoding()) {
            $db_encoding = $this->getConfig('db_encoding');
            $client_encoding = $this->getConfig('client_encoding');
            foreach ($bindings as $key => &$value) {
                // We need to transform all instances of DateTimeInterface into the actual
                // date string. Each query grammar maintains its own date string format
                // so we'll just ask the grammar for the format to get from the date.
                if ($value instanceof DateTimeInterface) {
                    $value = $value->format($grammar->getDateFormat());
                } elseif (false === $value) {
                    $value = 0;
                }
                if (is_string($value)) {
                    $value = $this->convertCharset($client_encoding, $db_encoding, $value);
                }
            }
        } else {
            foreach ($bindings as $key => &$value) {
                if ($value instanceof DateTimeInterface) {
                    $value = $value->format($grammar->getDateFormat());
                } elseif (false === $value) {
                    $value = 0;
                }
            }
        }

        return $bindings;
    }

    protected function convertCharset($in_encoding, $out_encoding, $value){

        //IGNORE
//        $encoding = mb_detect_encoding($value, mb_detect_order(), false);
//
//        if($encoding == $out_encoding)
//        {
//            return $value;
//        }
//        \Log::debug("encoding: ".$in_encoding." value ".$value);
        //return mb_convert_encoding(trim($value), $out_encoding);
        return iconv($in_encoding, "{$out_encoding}//IGNORE", trim($value));
    }

    public function select($query, $bindings = [], $useReadPdo = true)
    {
        $results = parent::select($query, $bindings, $useReadPdo);
        if ($this->isTransEncoding()) {
            if ($results) {
                $db_encoding = $this->getConfig('db_encoding');
                $client_encoding = $this->getConfig('client_encoding');
                if (is_array($results) || is_object($results)) {
                    foreach ($results as &$result) {
                        if (is_array($result) || is_object($result)) {
                            foreach ($result as $key=>&$value) {
                                if (is_string($value)) {
                                    $value = $this->convertCharset($db_encoding, $client_encoding, $value);
                                }
                            }
                        } elseif (is_string($result)) {
                                $result = $this->convertCharset($db_encoding, $client_encoding, $result);
                            }
                        }
                } elseif (is_string($results)) {
                        $results = $this->convertCharset($db_encoding, $client_encoding, $results);
                }
            }
        }

        return $results;
    }

    public function statement($query, $bindings = [])
    {

        return $this->run($query, $bindings, function ($query, $bindings) {
            if ($this->pretending()) {
                return true;
            }
            $count = substr_count($query, '?');
            if ($count == count($bindings)) {
                $bindings = $this->prepareBindings($bindings);

                return $this->getPdo()->prepare($query)->execute($bindings);
            }

            if (count($bindings) % $count > 0) {
                throw new \InvalidArgumentException('the driver can not support multi-insert.');
            }
            $mutiBindings = array_chunk($bindings, $count);
            $this->beginTransaction();
            try {
                $pdo  = $this->getPdo();
                $stmt = $pdo->prepare($query);

                foreach ($mutiBindings as $mutiBinding) {
                    $mutiBinding = $this->prepareBindings($mutiBinding);
                    $stmt->execute($mutiBinding);
                }
            } catch (\Exception $e) {
                $this->rollBack();

                return false;
            } catch (\Throwable $e) {
                $this->rollBack();

                return false;
            }
            $this->commit();

            return true;
        });
    }

    public function affectingStatement($query, $bindings = [])
    {
        return parent::affectingStatement($query, $bindings);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Illuminate\Database\Query\Processors\SqlServerProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new IfxProcessor();
    }

    protected function isTransEncoding()
    {
        $db_encoding = $this->getConfig('db_encoding');
        $client_encoding = $this->getConfig('client_encoding');

        return $db_encoding && $client_encoding && ($db_encoding != $client_encoding);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\SqlServerGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        $grammar = new QueryGrammar($this);
        $grammar->setTablePrefix($this->getTablePrefix());

        return $grammar;
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Schema\Grammars\SqlServerGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar());
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOInformix\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}