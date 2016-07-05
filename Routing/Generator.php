<?php

namespace MultipleApp\KernelBundle\Routing;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* This class help to build routes using the other app router
*/
class Generator
{
    protected $container;

    /**
    * @param ContainerInterface the symfony container
    */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
    * generate route using other routing generator
    *
    * @param string app name
    * @param string route name
    * @param array route params
    * @param boolean should we need to generate an absolute url
    *
    * @return string the url generated
    * @throws Symfony\Component\Routing\Exception\RouteNotFoundException if route do not exists
    */
    public function generate($appName, $name, $parameters = array(), $absolute = false)
    {
        $appUrlGenerator = $this->getAppUrlGenerator($appName);
        $url = $appUrlGenerator->generate($name, $parameters);

        // Fix controller name
        $url = str_replace($this->container->getParameter('kernel.name'), $appName, $url);

        // Manage absolue url
        if ($absolute) {
            $url = $this->container->getParameter('multiapp.'.$appName.'.base_url').$url;
        }

        return $url;
    }

    /**
    * Get the appEnvUrlGenerator from the cache of the other app (eg frontenddevUrlGenerator)
    *
    * @param string $appName the name of application
    * @return Symfony\Component\Routing\Generator\UrlGenerator
    */
    protected function getAppUrlGenerator($appName)
    {
        // Load the route generator for app
        $kernelName = '/'.$this->container->getParameter('kernel.name').'/';
        $appName = '/'.$appName.'/';
        $cache_dir = str_replace($kernelName, $appName, $this->container->getParameter('kernel.cache_dir'));
        $generatorClassName = $appName.ucfirst($this->container->getParameter('kernel.environment')).'UrlGenerator';
        require_once $cache_dir.DIRECTORY_SEPARATOR.$generatorClassName.'.php';

        return new $generatorClassName($this->container->get('router.request_context'));
    }
}
