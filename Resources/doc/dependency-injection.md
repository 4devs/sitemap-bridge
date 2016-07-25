Use with symfony [DependencyInjection](http://symfony.com/doc/current/components/dependency_injection/introduction.html)
========================

use with extension
------------------

```php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FDevs\Bridge\Sitemap\DependencyInjection\FDevsSitemapExtension;
use FDevs\Bridge\Sitemap\DependencyInjection\Compiler\AdapterPass;
use FDevs\Bridge\Sitemap\DependencyInjection\Compiler\FactoryPass;
use Symfony\Component\Routing\Router;

$container = new ContainerBuilder();
$container
    ->addCompilerPass(new AdapterPass())
    ->addCompilerPass(new FactoryPass())
    ->registerExtension(new FDevsSitemapExtension())
;
//init router service must implement Symfony\Component\Routing\RouterInterface
$container->register('router', /** *//);
$container->setParameter('kernel.root_dir', '/path/to/your/root/dir');

$options = [
    'uri' => 'http://simple.com',
    'web_dir' => null,
    'adapter' => [],
    'filename' => 'sitemap.xml',
    'parameters' => [],
];
$container->loadFromExtension('f_devs_sitemap', $options);
$container->compile();

$m = $container->get('f_devs_sitemap.sitemap_manager');
echo $m->get('sitemap')->xmlString();
```

load yml service
----------------

```php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

$container = new ContainerBuilder();

$container
    ->addCompilerPass(new AdapterPass())
    ->addCompilerPass(new FactoryPass());

//init router service must implement Symfony\Component\Routing\RouterInterface
$container->register('router', /** *//);

//init event_dispatcher service if you want use event listeners must implement Symfony\Component\EventDispatcher\EventDispatcherInterface
$container->register('event_dispatcher', /** *//);
//registred event listeners

$container->setParameter('f_devs_sitemap.uri', 'http://simple.com');
$container->setParameter('f_devs_sitemap.dir', '/path/to/your/web/dir');
$container->setParameter('f_devs_sitemap.params', []);

$reflection = new \ReflectionClass(SitemapEvents::class);
$loader = new XmlFileLoader($container, new FileLocator(dirname($reflection->getFileName()).'/Resources/config/'));
$loader->load('services.yml');

//if use with mongodb adapter
$container->setParameter('f_devs_sitemap.adapter.mongodb.class', /** class mondodb route model */);
$container->setParameter('f_devs_sitemap.adapter.mongodb.manager_name', /** manager name mondodb */);
$loader->load('adapter/mongodb.xml');


$container->compile();

$m = $container->get('f_devs_sitemap.sitemap_manager');
echo $m->get('sitemap')->xmlString();
```


If you use Symfony 2 framework, you could use our [sitemap bundle](https://github.com/4devs/sitemap-bundle)!