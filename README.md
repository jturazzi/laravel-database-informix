<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/dt/jturazzi/laravel-database-informix" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/v/jturazzi/laravel-database-informix" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/l/jturazzi/laravel-database-informix" alt="License"></a>
</p>

<h3 align="center">Laravel Database Informix is a Laravel Framework package designed to seamlessly integrate with the Informix Database Driver. It extends Illuminate/Database and works smoothly with the latest versions of Laravel. Tested on Laravel 10 and 11.</h3>

This work is inspired by the repository: [https://github.com/llaiajiale/laravel-ifx](https://github.com/llaiajiale/laravel-ifx)

## Prerequisites

Before installing this package, make sure you have the Informix SDK installed on your system. The Informix SDK is required for communication with the Informix database. You can download the SDK from the official IBM website.

Additionally, you'll need to ensure that the PDO Informix extension is compiled and installed in your PHP environment. This extension provides the necessary functionality for PHP to communicate with Informix databases. You can find more information about the PDO Informix extension [here](https://pecl.php.net/package/PDO_INFORMIX).

For simplified installation of the Informix SDK and PDO extension, check out the scripts available in [this repository](https://github.com/jturazzi/informix-toolbox).

## Installation

To include Laravel-database-informix in your project, run the following command:

```bash
composer require jturazzi/laravel-database-informix
```

After Composer has finished installing the package, you'll need to register Informix DB. 

### For Laravel 10 and earlier versions:
Go to config/app.php, find the providers packages array, and add:
```php
/*
* Package Service Providers...
*/
jturazzi\Informix\InformixDBServiceProvider::class,
```

### For Laravel 11 and newer versions:
Go to bootstrap/providers.php and add:
```php
return [
    App\Providers\AppServiceProvider::class,
    jturazzi\Informix\InformixDBServiceProvider::class,
];
```

Finally, publish the configuration file with the following Artisan command:
```php
php artisan vendor:publish
```

This command will duplicate the configuration file into config/informix.php.

Configure the Informix database connection details in this file.

## Contribution

We welcome community contributions. Fork, make changes, submit a pull request.


## License

This project is licensed under the [MIT](LICENSE) License.