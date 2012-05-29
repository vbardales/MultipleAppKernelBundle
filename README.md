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

Add it to the `AppKernel` class:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        // Multiple App
        new MultipleApp\KernelBundle\MultipleAppKernelBundle(),
    );

    // ...
}
```