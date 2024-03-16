<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/dt/jturazzi/laravel-database-informix" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/v/jturazzi/laravel-database-informix" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jturazzi/laravel-database-informix"><img src="https://img.shields.io/packagist/l/jturazzi/laravel-database-informix" alt="License"></a>
</p>

<h3 align="center">Laravel Database Informix est un package conçu pour le framework Laravel afin d'intégrer de manière transparente avec le pilote de base de données Informix. Il étend Illuminate/Database et fonctionne parfaitement avec les dernières versions de Laravel. Testé sur Laravel 10 et 11.</h3>

Ce travail est inspiré par le dépôt : [https://github.com/llaiajiale/laravel-ifx](https://github.com/llaiajiale/laravel-ifx)

## Prérequis

Avant d'installer ce package, assurez-vous d'avoir le SDK Informix installé sur votre système. Le SDK Informix est requis pour la communication avec la base de données Informix. Vous pouvez télécharger le SDK depuis le site officiel d'IBM.

De plus, assurez-vous que l'extension PDO Informix est compilée et installée dans votre environnement PHP. Cette extension fournit les fonctionnalités nécessaires à PHP pour communiquer avec les bases de données Informix. Vous pouvez trouver plus d'informations sur l'extension PDO Informix [ici](https://pecl.php.net/package/PDO_INFORMIX).

Pour simplifier l'installation du SDK Informix et de l'extension PDO, consultez les scripts disponibles dans [ce dépôt](https://github.com/jturazzi/informix-toolbox).

## Installation

Pour inclure Laravel Database Informix dans votre projet, exécutez la commande suivante :

```bash
composer require jturazzi/laravel-database-informix
```

Une fois que Composer a terminé l'installation du package, vous devrez enregistrer Informix DB.

### Pour Laravel 10 et les versions antérieures :
Allez dans config/app.php, trouvez le tableau providers et ajoutez :
```php
/*
* Package Service Providers...
*/
jturazzi\Informix\InformixDBServiceProvider::class,
```

### Pour Laravel 11 et les versions plus récentes :
Allez dans bootstrap/providers.php et ajoutez :
```php
return [
    App\Providers\AppServiceProvider::class,
    jturazzi\Informix\InformixDBServiceProvider::class,
];
```

Enfin, publiez le fichier de configuration avec la commande Artisan suivante :
```php
php artisan vendor:publish
```

Cette commande dupliquera le fichier de configuration dans config/informix.php.

Configurez les détails de connexion à la base de données Informix dans ce fichier.

## Contribution

Contributions bienvenues de la communauté. Fork, apportez des modifications, soumettez une pull request.

## Licence

Ce projet est sous licence [MIT](LICENSE).