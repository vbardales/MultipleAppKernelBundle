MultipleAppKernelBundle
=======================

Allow Symfony2 app to host multiple applications.

## Installation

``` bash
git clone git://github.com/vbardales/MultipleAppKernelBundle.git vendor/bundles/MultipleApp/KernelBundle
```

Or using deps file

```
[AdmingeneratorGeneratorBundle]
    git=git://github.com/vbardales/MultipleAppKernelBundle.git
    target=/bundles/MultipleApp/KernelBundle
    version=origin/master
```

Or using composer.json

```
    "vbardales/multiple-app-kernel-bundle": "dev-master",
```

Register it in the `autoload.php` file:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'MultipleApp'    => __DIR__.'/../vendor/bundles',
));
```

## New structure

Common files (config, Resources, ...) are located in `/commons` folder. Apps files are located in each app folder.

# Commons

You can rename your `/app` folder to `/commons`. `console` and `AppKernel.php` files are no more required in this folder.

`/commons` must contain `autoload.php` initially existing in old `/app` folder and `BaseKernel.php`, which should look like :

``` php
<?php

use MultipleApp\KernelBundle\Kernel\Kernel;

abstract class BaseKernel extends Kernel
{
    public function registerCommonsBundles()
    {
        $bundles = array(
            // ...
            
            // Multiple App
            new MultipleApp\KernelBundle\MultipleAppKernelBundle(),
        );
        
        // ...
        
        return $bundles;
    }
}
```

# App Kernels

Apps files are located in each app folder. Each app folder (like `/backend`) should contain :
- `AppCache.php`
- `console` where requires must be updated like this

``` php
#!/usr/bin/env php
<?php
    // ...
    
    require_once __DIR__.'/../commons/bootstrap.php.cache';
    require_once __DIR__.'/AppKernel.php';

    // ...
```

`console` can be found in your old `/app` folder.

- `AppKernel.php` which should look like :

``` php
<?php

require_once __DIR__.'/../commons/BaseKernel.php';

use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends BaseKernel
{
    public function registerAppBundles()
    {
        $bundles = array(
            // ...
        );

        // ...
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

```
