Requirements
------------
 - PHP >= 7.3.0
 - Laravel >= 7.0.0
 - Fileinfo PHP Extension

Installation
------------

> This package requires PHP 7.3+ and Laravel 7.0 or up

First, install laravel (7.0 / 8.0 or up), and make sure that the database connection settings are correct.

```
composer require open-developer/open-developer
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="OpenDeveloper\Developer\DeveloperServiceProvider"
```
After run command you can find config file in `config/developer.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install.
```
php artisan developer:install
```

Open `http://localhost/developer/` in browser,use username `developer` and password `developer` to login.

Updating
------------
Updating to a new version of open-developer may require updating assets you can publish them using:
```
php artisan vendor:publish --tag=open-developer-assets --force
```

Configurations
------------
The file `config/developer.php` contains an array of configurations, you can find the default configurations in there.
