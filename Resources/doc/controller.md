Use with symfony controller
==========================

basic usage
-----------

```php
<?php
use FDevs\Sitemap\SitemapManager;
use FDevs\Bridge\Sitemap\Controller\SitemapController;

$manager = new SitemapManager();//init your manager [example](https://github.com/4devs/sitemap) 
$params = [];//add your diferent params

$controller = new SitemapController($manager, $params);

echo (string) $controller->indexAction();//HTTP string
```

usage with [silex](http://silex.sensiolabs.org/)
------------------------------------------------

```php
<?php
use Silex\Application;
use FDevs\Sitemap\SitemapManager;
use FDevs\Bridge\Sitemap\Controller\SitemapController;

$app = new Application();

$app['sitemap.manager'] = function() {
    return new SitemapManager();//init your manager [example](https://github.com/4devs/sitemap) 
};
$app['sitemap.params'] = [];//add your diferent params

$app['sitemap.controller'] = function() use ($app) {
    return new SitemapController($app['sitemap.manager'],$app['sitemap.params']);
};

$app->get('/sitemap.xml', "sitemap.controller:indexAction");
```
