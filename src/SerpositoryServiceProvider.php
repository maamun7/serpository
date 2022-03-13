<?php

namespace Maamun7\Serpository;

use Illuminate\Support\ServiceProvider;
use Maamun7\Serpository\Console\Commands\MakeRepoCommand;
use Maamun7\Serpository\Console\Commands\MakeRepositoryCommand;
use Maamun7\Serpository\Console\Commands\MakeServiceRepositoryCommand;

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

    /**
     * Bind Interface & Repository
     *
     * @return void
     */
    private function registerRepositories()
    {
        foreach ($this->getBindableRepositories() as $repo) {
            $this->app->bind($repo['interface'], $repo['repository']);
        }
    }
}
