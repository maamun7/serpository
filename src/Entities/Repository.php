<?php

namespace Serpository\Entities;

class Repository
{
    public string $title;
    public string $path;
    public string $namespace;
    public string $interfaceName;
    public string $interfaceNamespace;

    public function __construct(
        string $title,
        string $path,
        string $namespace,
        string $interfaceName,
        string $interfaceNamespace
    )
    {
        $this->title = $title;
        $this->path = $path;
        $this->namespace = $namespace;
        $this->interfaceName = $interfaceName;
        $this->interfaceNamespace = $interfaceNamespace;
    }
}
