<?php

namespace MultipleApp\KernelBundle\Kernel;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel
{
    /**
     * Returns the file path for a given resource.
     *
     * A Resource can be a file or a directory.
     *
     * The resource name must follow the following pattern:
     *
     *     @<BundleName>/path/to/a/file.something
     *
     * where BundleName is the name of the bundle
     * and the remaining part is the relative path in the bundle.
     *
     * If $dir is passed, and the first segment of the path is "Resources",
     * foreach $dir element, this method will look for a file named:
     *
     *     $dirElement/<BundleName>/path/without/Resources
     *
     * before looking in the bundle resource folder.
     *
     * @param string  $name  A resource name to locate
     * @param string  $dir   A directory where to look for the resource first
     * @param Boolean $first Whether to return the first path or paths for all matching bundles
     *
     * @return string|array The absolute path of the resource or an array if $first is false
     *
     * @throws \InvalidArgumentException if the file cannot be found or the name is not valid
     * @throws \RuntimeException         if the name contains invalid/unsafe
     * @throws \RuntimeException         if a custom resource is hidden by a resource in a derived bundle
     *
     * @api
     */
    public function locateResource($name, $dir = null, $first = true)
    {
        if ('@' !== $name[0]) {
            throw new \InvalidArgumentException(sprintf('A resource name must start with @ ("%s" given).', $name));
        }

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('File name "%s" contains invalid characters (..).', $name));
        }

        $dirs = null === $dir ? array() : $dir;

        if (!is_array($dirs)) {
            throw new \InvalidArgumentException(sprintf('Directories must be an array (..).', $name));
        }

        $bundleName = substr($name, 1);
        $path = '';
        if (false !== strpos($bundleName, '/')) {
            list($bundleName, $path) = explode('/', $bundleName, 2);
        }

        $isResource = 0 === strpos($path, 'Resources') && 0 !== count($dirs);
        $overridePath = substr($path, 9);
        $resourceBundle = null;
        $bundles = $this->getBundle($bundleName, false);
        $files = array();

        foreach ($bundles as $bundle) {
            foreach ($dirs as $dir) {
                if ($isResource && file_exists($file = $dir . '/' . $bundle->getName() . $overridePath)) {
                    if (null !== $resourceBundle) {
                        throw new \RuntimeException(sprintf('"%s" resource is hidden by a resource from the "%s" derived bundle. Create a "%s" file to override the bundle resource.',
                            $file,
                            $resourceBundle,
                            $dir . '/' . $bundles[0]->getName() . $overridePath
                        ));
                    }

                    if ($first) {
                        return $file;
                    }
                    $files[] = $file;
                }
            }

            if (file_exists($file = $bundle->getPath() . '/' . $path)) {
                if ($first && !$isResource) {
                    return $file;
                }
                $files[] = $file;
                $resourceBundle = $bundle->getName();
            }
        }

        if (count($files) > 0) {
            return $first && $isResource ? $files[0] : $files;
        }

        throw new \InvalidArgumentException(sprintf('Unable to find file "%s".', $name));
    }

    /**
     * Register specific bundles for each app
     * @return array Registered specific bundles
     */
    abstract public function registerAppBundles();

    /**
     * Register common bundles for all apps
     * @return array Registered common bundles
     */
    abstract public function registerCommonsBundles();

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\KernelInterface::registerBundles()
     */
    public function registerBundles()
    {
        return array_merge($this->registerCommonsBundles(), $this->registerAppBundles());
    }
}
