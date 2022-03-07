<?php

namespace Serpository\Generators;

use Exception;
use Serpository\Str;
use Serpository\Entities\Repository;

class RepositoryGenerator extends Generator
{
    /**
     * Generate Repository
     *
     * @param $name
     *
     * @return Repository
     * @throws Exception
     */
    public function generate($name): Repository
    {
        $repository = Str::repository($name);
        $interface = Str::repositoryInterface($name);
        $filePath = $this->getRepositoriesFilePath($repository);

        if ($this->exists($filePath)) {
            throw new Exception('Repository already exists.');
        }

        $reposDirectories = [
            $this->getRepositoriesRootPath(),
            $this->getInterfacesRootPath(),
        ];

        foreach ($reposDirectories as $dir) {
            if (!$this->exists($dir)) {
                $this->createDirectory($dir);
                $this->createFile($dir . '/.gitkeep');
            }
        }

        $namespace = $this->getRepositoryNamespace();
        $interfaceNamespace = $this->getInterfaceNamespace();

        $this->createRepository($namespace, $repository, $filePath, $interface, $interfaceNamespace);
        $this->createInterface($interface, $interfaceNamespace);

        return new Repository(
            $repository,
            $this->relativeFromReal($filePath),
            $namespace,
            $interface,
            $interfaceNamespace,
        );
    }

    /**
     * Create Repository and inject Interface
     *
     * @param $namespace
     * @param $repository
     * @param $filePath
     * @param $interface
     * @param $interfaceNamespace
     *
     * @return void
     */
    public function createRepository($namespace, $repository, $filePath, $interface, $interfaceNamespace): void
    {
        $modelStub = $this->findModel($repository);
        $content = file_get_contents($this->getStub());
        $content = str_replace(
            [
                '{{namespace}}',
                '{{repository}}',
                '{{interface}}',
                '{{interfaceNamespace}}',
                '{{modelType}}',
                '{{modelNamespace}}',
                '{{modelProperty}}',
                '{{bindProperty}}',
                '{{paramName}}',
            ],

            [
                $namespace,
                $repository,
                $interface,
                $interfaceNamespace,
                ...$modelStub
            ],

            $content
        );

        $this->createFile($filePath, $content);
    }

    /**
     * Find Model by concatenating few formats with the provided Repository base name
     *
     * @param string $repositoryName
     *
     * @return array
     */
    public function findModel(string $repositoryName): array
    {
        $modelName = Str::model($repositoryName);
        $modelFilePath = $this->getModelsRootPath();

        foreach (['', 's', 'Model', '_model'] as $concatStr) {
            $model = $modelName . $concatStr;

            if ($this->exists($modelFilePath . '/' . $model . '.php')) {
                $modelName = $model;
                break;
            }
        }

        return [
            $modelName,
            'use ' . $this->getModelNamespace() . '\\' . $modelName,
            'protected ' . $modelName . ' $' . strtolower($modelName),
            '$this->' . strtolower($modelName),
            '$' . strtolower($modelName),
        ];
    }

    /**
     * Create Interface
     *
     * @param $interface
     * @param $namespace
     *
     * @return void
     */
    public function createInterface($interface, $namespace): void
    {
        $filePath = $this->getInterfacesFilePath($interface);
        $content = file_get_contents($this->getInterfaceStub());

        $content = str_replace(
            ['{{namespace}}', '{{interface}}'],
            [$namespace, $interface],
            $content
        );

        $this->createFile($filePath, $content);
    }

    /**
     * Get the stub file for generating Repository.
     *
     * @return string
     */
    public function getStub(): string
    {
        return __DIR__ . '/../Generators/stubs/repository.stub';
    }

    /**
     * Get the stub file for generating Interface.
     *
     * @return string
     */
    public function getInterfaceStub(): string
    {
        return __DIR__ . '/../Generators/stubs/interface.stub';
    }
}
