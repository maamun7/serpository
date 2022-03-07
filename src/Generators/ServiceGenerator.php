<?php

namespace Serpository\Generators;

use Exception;
use Serpository\Str;
use Serpository\Entities\Repository;
use Serpository\Entities\Service;

class ServiceGenerator extends Generator
{
    /**
     * Generate Service & Repository
     *
     * @param $name
     * @param Repository|null $repository
     *
     * @return Service
     * @throws Exception
     */
    public function generate($name, Repository|null $repository): Service
    {
        $service = Str::service($name);
        $filePath = $this->getServiceFilePath($service);

        if ($this->exists($filePath)) {
            throw new Exception('Service already exists.');
        }

        $serviceRootDir = $this->getServicesRootPath();

        if (!$this->exists($serviceRootDir)) {
            $this->createDirectory($serviceRootDir);
            $this->createFile($serviceRootDir . '/.gitkeep');
        }

        $namespace = $this->getServiceNamespace();
        $stub = !$repository ? $this->getStub() : $this->getRepoInjectedService();
        $repoInfo = $this->getRepoInfo($repository);

       // dd($repoInfo);
        $content = file_get_contents($stub);
        $content = str_replace(
            [
                '{{namespace}}',
                '{{service}}',
                '{{interfaceNamespace}}',
                '{{interfaceName}}',
                '{{paramName}}',
            ],

            [
                $namespace,
                $service,
                ...$repoInfo
            ],

            $content
        );

        $this->createFile($filePath, $content);

        return new Service(
            $service,
            $this->relativeFromReal($filePath),
            $namespace,
        );
    }

    /**
     * @param Repository|null $repository
     *
     * @return array
     */
    public function getRepoInfo(Repository|null $repository): array
    {
        if ($repository === null) {
            return [];
        }

        return [
            $repository->interfaceNamespace,
            $repository->interfaceName,
            lcfirst($repository->title),
        ];
    }

    /**
     * Get the stub file for generating Service.
     *
     * @return string
     */
    public function getStub(): string
    {
        return __DIR__ . '/../Generators/stubs/service.stub';
    }

    /**
     * Get ths stub file for generating Repository Injected Service
     *
     * @return string
     */
    public function getRepoInjectedService(): string
    {
        return __DIR__ . '/../Generators/stubs/injectedService.stub';
    }
}
