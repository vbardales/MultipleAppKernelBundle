<?php

namespace MultipleApp\KernelBundle\FileLocator;

use Symfony\Component\Config\FileLocator as BaseFileLocator;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * FileLocator uses the KernelInterface to locate resources in bundles.
 */
class FileLocator extends BaseFileLocator
{
    private $kernel;

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel A KernelInterface instance
     * @param string          $paths   An array of paths where to look for resources
     */
    public function __construct(KernelInterface $kernel, array $paths = array())
    {
        $this->kernel = $kernel;

        parent::__construct($paths);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($file, $currentPath = null, $first = true)
    {
        if ('@' === $file[0]) {
            return $this->kernel->locateResource($file, $this->paths, $first);
        }

        return parent::locate($file, $currentPath, $first);
    }
}
