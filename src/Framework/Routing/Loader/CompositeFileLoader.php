<?php

namespace Framework\Routing\Loader;

class CompositeFileLoader implements FileLoaderInterface
{
    private $loaders;

    public function __construct()
    {
        $this->loaders = [];
    }

    public function add(FileLoaderInterface $loader)
    {
        if (!in_array($loader, $this->loaders)) {
            $this->loaders[] = $loader;
        }
    }

    public function load($path)
    {
        $routes = null;
        foreach ($this->loaders as $loader) {
            if ($routes = $this->tryLoadFile($loader, $path)) {
                return $routes;
            }
        }

        throw new UnsupportedFileTypeException(sprintf(
            'No compatible loader found for file %s.',
            $path
        ));
    }

    private function tryLoadFile(FileLoaderInterface $loader, $path)
    {
        try {
            return $loader->load($path);
        } catch (UnsupportedFileTypeException $e) {
            // do nothing
        }
    }
}
