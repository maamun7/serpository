<?php

namespace Serpository\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait Command
{
    /**
     * @var InputInterface
     */
    protected InputInterface $input;

    /**
     * @var OutputInterface
     */
    protected OutputInterface $output;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description);

        foreach ($this->getArguments() as $arguments) {
            call_user_func_array([$this, 'addArgument'], $arguments);
        }

        foreach ($this->getOptions() as $options) {
            call_user_func_array([$this, 'addOption'], $options);
        }
    }

    /**
     * Default implementation to get the arguments of this command.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [];
    }

    /**
     * Default implementation to get the options of this command.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        return (int) $this->handle();
    }

    /**
     * Get an argument from the input.
     *
     * @param string $key
     *
     * @return string
     */
    public function argument(string $key): string
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get an option from the input.
     *
     * @param string $key
     *
     * @return string
     */
    public function option(string $key): string
    {
        return $this->input->getOption($key);
    }

    /**
     * Write a string as information output.
     *
     * @param string $string
     */
    public function printOutput(string $string): void
    {
        $this->output->writeln("<info>$string</info>");
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @return void
     */
    public function comment(string $string): void
    {
        $this->output->writeln("<comment>$string</comment>");
    }

    /**
     * Write a string as error output.
     *
     * @param string $string
     * @return void
     */
    public function error(string $string): void
    {
        $this->output->writeln("<error>$string</error>");
    }
}
