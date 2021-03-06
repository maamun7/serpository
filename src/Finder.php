<?php

namespace Maamun7\Serpository;

use \Exception;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

trait Finder
{
    /**
     * Get the root directory of the application.
     *
     * @return string
     */
    public function getSourceRoot(): string
    {
        return app_path();
    }

    /**
     * Get file path of service
     *
     * @param string $service
     *
     * @return string
     */
    public function getServiceFilePath(string $service): string
    {
        return (!$service) ? app_path() : $this->getServicesRootPath() . DS . $service . '.php';
    }

    /**
     * Get root directory of services.
     *
     * @return string
     */
    public function getServicesRootPath(): string
    {
        return $this->getSourceRoot() . DS . 'Services';
    }

    /**
     * Get file path of repository
     *
     * @param string $repository
     *
     * @return string
     */
    public function getRepositoriesFilePath(string $repository): string
    {
        if ((!$repository)) {
            return app_path();
        } else {
            return $this->getRepositoriesRootPath() . DS . 'Eloquents' . DS . $repository . '.php';
        }
    }

    /**
     * Get root path of interfaces
     *
     * @param string $interface
     *
     * @return string
     */
    public function getInterfacesFilePath(string $interface): string
    {
        return (!$interface) ? app_path() : $this->getInterfacesRootPath() . DS . $interface . '.php';
    }

    /**
     * Get root directory of repositories
     *
     * @return string
     */
    public function getRepositoriesRootPath(): string
    {
        return $this->getSourceRoot() . DS . 'Repositories';
    }

    /**
     * Find the root path of all the Eloquent Repositories.
     *
     * @return string
     */
    public function getEloquentRootPath(): string
    {
        return $this->getRepositoriesRootPath() . DS . 'Eloquents';
    }

    /**
     * Get root path of interface
     *
     * @return string
     */
    public function getInterfacesRootPath(): string
    {
        return $this->getRepositoriesRootPath() . DS . 'Interfaces';
    }

    /**
     * Get root directory of models
     *
     * @return string
     */
    public function getModelsRootPath(): string
    {
        return $this->getSourceRoot(). DS .'Models';
    }

    /**
     * Check if a file or directory exists.
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
        $composer = json_decode(file_get_contents(base_path() . DS . 'composer.json'), true);

        // see which one refers to the "src/" directory
        foreach ($composer['autoload']['psr-4'] as $namespace => $directory) {
            $directory = str_replace(['/', '\\'], DS, $directory);
            if ($directory === $dir . DS) {
                return trim($namespace, '\\');
            }
        }

        throw new \Exception('App namespace not set in composer.json');
    }


    /**
     * Get app namespace
     *
     * @return string
     *
     * @throws Exception
     */
    public function getAppNamespace(): string
    {
        return $this->findNamespace('app');
    }


    /**
     * Get service namespace
     *
     * @return string
     *
     * @throws Exception
     */
    public function getServiceNamespace(): string
    {
        return $this->getAppNamespace() . '\\Services';
    }

    /**
     * Get repository namespace
     *
     * @return string
     *
     * @throws Exception
     */
    public function getRepositoryNamespace(): string
    {
        return $this->getAppNamespace() . '\\Repositories';
    }

    /**
     * Get interface namespace
     *
     * @return string
     *
     * @throws Exception
     */
    public function getEloquentNamespace(): string
    {
        return $this->getRepositoryNamespace() . '\\Eloquents';
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
     * Get app directory name
     *
     * @return string
     */
    protected function getSourceDirectoryName(): string
    {
        return 'app';
    }

    /**
     * @throws Exception
     */
    protected function getBindableRepositories(): array
    {
        $repositories = [];
        $repoDir = $this->getEloquentRootPath();

        $repoNamespace = $this->getEloquentNamespace();
        $interfaceNamespace = $this->getInterfaceNamespace();

        foreach (glob("{$repoDir}/*.php") as $filename) {
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

    /**
     * Get application model directory name
     * @return string
     *
     * @throws Exception
     */
    public function getModelNamespace(): string
    {
        return $this->getAppNamespace() . '\\Models';
    }
}
