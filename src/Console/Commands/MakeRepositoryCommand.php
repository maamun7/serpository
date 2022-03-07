<?php

namespace Serpository\Console\Commands;

use Exception;
use Serpository\Console\Command;
use Serpository\Generators\RepositoryGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeRepositoryCommand extends SymfonyCommand
{
    use Command;
    /**
     * The console command name.
     *
     * @var string
     */
    protected string $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Create a new Repository.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $generator = new RepositoryGenerator();
        $repositoryName = $this->argument('repository');

        try {
            $repository = $generator->generate($repositoryName);

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
     * Get the console command arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The Repository\'s name.']
        ];
    }
}
