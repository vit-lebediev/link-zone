Link Zone Project
========================

This is main repository for Link Zone project.

1) Technologies used
----------------------------------

This project is built upon next technologies:
* PHP 5.4 (Planning to move to PHP 5.5)
* PostgreSQL as Data Storage
* Symfony2 (2.2) PHP Framework (Planing to move to 2.3)
* jQuery for UI (Planning to use AngularJS + Twitter Bootstrap)
* Twig Templating Engine
* [Composer](http://getcomposer.org/) to manage dependencies

2) Symfony Bundles used
* [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) as user registration/authorization implementation
* [DoctrineMigrationsBundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html) in order to manage database migrations
* [KnpMenyBundle](https://github.com/KnpLabs/KnpMenuBundle)
* [FPNTagBundle](https://github.com/FabienPennequin/FPNTagBundle)

3) Deployment
----------------------------------

In order to deploy the application, you need to perform next steps:

### 1) Clone repository
  `git clone git@github.com:Malgin/link-zone.git LinkZone && cd LinkZone`
### 2) Fetch dependencies
If you do not have composer installed, you can get it installed by issuing `curl -s http://getcomposer.org/installer | php`
Then, in order to install dependencies, run
  `php composer.phar install`

### 3) Deploy Database Structure
For this, first edit `app/config/parameters.yml` file and set proper database credentials. Then issue
  `php app/console doctrine:migrations:migrate`
This will update your database to the most recent version  
