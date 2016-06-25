Use with symfony console
========================

add the command into your application
-------------------------------------

```php
#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use FDevs\Bridge\Sitemap\Command\GenerateCommand;
use Symfony\Component\Console\Application;

//init sitemap manager
$sitemapManager = ...;

//init your params
$params = [];

$fileName = 'sitemap.xml';
$webDir = '/path/to/web/dir/';

$application = new Application();
$application->add(new GenerateCommand($sitemapManager, $fileName, $webDir, $params));
$application->run();
```

use the command
---------------

```bash
php application.php fdevs:sitemap:generate
```
