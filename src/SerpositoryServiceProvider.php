<?php

namespace Serpository;

use Illuminate\Support\ServiceProvider;
use Serpository\Console\Commands\MakeRepoCommand;
use Serpository\Console\Commands\MakeRepositoryCommand;
use Serpository\Console\Commands\MakeServiceRepositoryCommand;

class SerpositoryServiceProvider extends ServiceProvider
{
    use Finder;

    private array $command = [
        MakeRepoCommand::class,
        MakeRepositoryCommand::class,
        MakeServiceRepositoryCommand::class,
    ];

    public function register()
    {
        $this->commands($this->command);
        $this->registerRepositories();
    }

    public function boot()
    {

    }

    private function registerRepositories()
    {
        foreach ($this->getBindableRepositories() as $repo) {
            $this->app->bind($repo['interface'], $repo['repository']);
        }
    }
}
