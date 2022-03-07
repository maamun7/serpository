<?php

namespace Serpository\Entities;

class Service
{
    public string $title;
    public string $path;
    public string $namespace;

    public function __construct(string $title, string $path, string $namespace)
    {
        $this->title = $title;
        $this->path = $path;
        $this->namespace = $namespace;
    }
}
