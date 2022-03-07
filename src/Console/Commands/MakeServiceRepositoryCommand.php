<?php

namespace Serpository\Console\Commands;

use Exception;
use Serpository\Console\Command;
use Serpository\Entities\Repository;
use Serpository\Generators\ServiceGenerator;
use Serpository\Generators\RepositoryGenerator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeServiceRepositoryCommand extends SymfonyCommand
{
    use Command;

    /**
     * The console command name.
     *
     * @var string
     */
    protected string $name = 'make:service {--R|r} {--RP|repo=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Create a new Service and a Repository.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $repository = null;
        $serviceName = $this->argument('service');
        $hasRepo = (int) $this->option('r');
        $repo = $this->option('repo');

        if ($repo !== '' || $hasRepo === 1) {
            $repo =  $repo !== '' ? $repo : $serviceName;
            $repository = $this->createRepository($repo);
        }

        try {
            $generator = new ServiceGenerator();

            $service = $generator->generate($serviceName, $repository);

            $this->printOutput(
                "Service class created successfully." .
                "\n" .
                "\n" .
                "Find it at <comment>" . $service->path . "</comment>" . "\n"
            );

            if ($repository === null) {
                die();
            }

            $this->printOutput(
                "Repository class created successfully." .
                "\n" .
                "\n" .
                "Find it at <comment>" . $repository->path . "</comment>" . "\n"
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Create Repository with injecting interface
     *
     * @param $repositoryName
     *
     * @return Repository|null
     */
    public function createRepository($repositoryName): Repository|null
    {
        $repository = null;
        $generator = new RepositoryGenerator();

        try {
            $repository = $generator->generate($repositoryName);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return $repository;
    }

    public function getOptions(): array
    {
        return [
            ['r', 'R', InputOption::VALUE_NONE, 'Whether a repository will create or not.'],
            ['repo', 'RP', InputOption::VALUE_OPTIONAL, 'If Repository has different name.', ''],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [
            ['service', InputArgument::REQUIRED, 'The Service\'s name.'],
        ];
    }
}
