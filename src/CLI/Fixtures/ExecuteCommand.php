<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends Command
{
    private $repository;

    public function __construct(FixtureRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:fixtures:execute');
        $this->setDescription('Executes the package fixtures.');
        $this->setHelp('This command allows you to execute all fixtures in a the environment.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixtures = $this->repository->findAll();

        if(count($fixtures->toCollection()) === 0) {
            echo "No fixture available!";
        }

        foreach($fixtures->toCollection() as $fixture) {
            $fixture->execute();
        }

        echo "Fixtures executed successfully.";
    }
}