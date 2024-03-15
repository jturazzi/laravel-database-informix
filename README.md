<h1 align="center">Laravel Database Informix</h1>
<h3 align="center">Laravel Database Informix is a Laravel Framework package designed to seamlessly integrate with the Informix Database Driver. It extends Illuminate/Database and works smoothly with the latest versions of Laravel.</h3>

This work is inspired by the repository: [https://github.com/llaiajiale/laravel-ifx](https://github.com/llaiajiale/laravel-ifx)

## Installation

To include Laravel-database-informix in your project, run the following command:

```bash
composer require jturazzi/laravel-database-informix
```

After Composer has finished installing the package, you'll need to register Informix DB. 

Go to boostrap/providers.php, find the providers array, and add:

```php
return [
    App\Providers\AppServiceProvider::class,
    jturazzi\Database\Informix\InformixDBServiceProvider::class,
];
```

Finally, publish the configuration file with the following Artisan command:
```php
php artisan vendor:publish
```

This command will duplicate the configuration file into config/informix.php.

## License

This project is licensed under the [MIT](LICENSE) License.