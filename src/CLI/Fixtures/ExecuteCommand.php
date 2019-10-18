<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\CLI\Fixtures\FixtureRepository;
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
        $this->setHelp('This command allows you to execute the fixtures of the installed packages.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixtures = $this->repository->findAll();

        if($fixtures->isEmpty()) {
            echo "No fixture available!";
            return;
        }

        foreach($fixtures->toSortedArray() as $fixture) {
            $fixture->execute();
        }

        echo "Fixtures executed successfully.";
    }
}