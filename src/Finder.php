<?php

namespace Serpository;

use \Exception;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

trait Finder
{
    /**
     * get the root of the source directory.
     *
     * @return string
     */
    public function getSourceRoot(): string
    {
        return app_path();
    }

    /**
     * @param string $service
     *
     * @return string
     */
    public function getServiceFilePath(string $service): string
    {
        return (!$service) ? app_path() : $this->getServicesRootPath(). DS . $service . '.php';
    }

    /**
     * Find the root path of all the services.
     *
     * @return string
     */
    public function getServicesRootPath(): string
    {
        return $this->getSourceRoot(). DS .'Services';
    }

    /**
     * @param string $repository
     *
     * @return string
     */
    public function getRepositoriesFilePath(string $repository): string
    {
        return (!$repository) ? app_path() : $this->getRepositoriesRootPath(). DS . $repository . '.php';
    }

    public function getInterfacesFilePath(string $interface): string
    {
        return (!$interface) ? app_path() : $this->getInterfacesRootPath(). DS . $interface . '.php';
    }

    /**
     * Find the root path of all the repositories.
     *
     * @return string
     */
    public function getRepositoriesRootPath(): string
    {
        return $this->getSourceRoot(). DS .'Repositories';
    }

    /**
     * Find the root path of all the interfaces of all interfaces.
     *
     * @return string
     */
    public function getInterfacesRootPath(): string
    {
        return $this->getRepositoriesRootPath(). DS .'Interfaces';
    }

    /**
     * Determine if a file or directory exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @param string $dir
     * @return string
     *
     * @throws Exception
     */
    public function findNamespace(string $dir): string
    {
        // read composer.json file contents to determine the namespace
        $composer = json_decode(file_get_contents(base_path(). DS .'composer.json'), true);

        // see which one refers to the "src/" directory
        foreach ($composer['autoload']['psr-4'] as $namespace => $directory) {
            $directory = str_replace(['/', '\\'], DS, $directory);
            if ($directory === $dir.DS) {
                return trim($namespace, '\\');
            }
        }

        throw new \Exception('App namespace not set in composer.json');
    }


    /**
     * @return string
     *
     * @throws Exception
     */
    public function getAppNamespace(): string
    {
        return $this->findNamespace('app');
    }


    /**
     * @return string
     *
     * @throws Exception
     */
    public function getServiceNamespace(): string
    {
        return $this->getAppNamespace() . '\\Services';
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getRepositoryNamespace(): string
    {
        return $this->getAppNamespace() . '\\Repositories';
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getInterfaceNamespace(): string
    {
        return $this->getRepositoryNamespace() . '\\Interfaces';
    }

    /**
     * @param string $path
     * @param string $needle
     *
     * @return string
     */
    protected function relativeFromReal(string $path, string $needle = ''): string
    {
        if (!$needle) {
            $needle = $this->getSourceDirectoryName() . DS;
        }

        return strstr($path, $needle);
    }

    /**
     * @return string
     */
    protected function getSourceDirectoryName(): string
    {
        return 'app';
    }

    protected function getBindableRepositories(): array
    {
        $repositories = [];
        $repoDir = $this->getRepositoriesRootPath();

        $repoNamespace = $this->getRepositoryNamespace();
        $interfaceNamespace = $this->getInterfaceNamespace();

        foreach (glob("{$repoDir}/*.php") as $filename)
        {
            $repository = str_replace('.php', '', basename($filename));
            $interface =  $repository . 'Interface';

            if ($this->exists($this->getInterfacesFilePath($interface))) {
                $repositories[] = [
                    'interface' => $interfaceNamespace . '\\' . $interface,
                    'repository' => $repoNamespace . '\\' . $repository
                ];
            }
        }

        return $repositories;
    }

    public function getModelsRootPath(): string
    {
        return $this->getSourceRoot(). DS .'Models';
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getModelNamespace(): string
    {
        return $this->getAppNamespace() . '\\Models';
    }
}
