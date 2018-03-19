<?php


namespace AppBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    public function configure()
    {
        $this
            ->setName("show:user:create")
            ->setDescription("Creates a user")
            ->addArgument('email', InputArgument::REQUIRED, 'User\'s email')
            ->addArgument('password', InputArgument::REQUIRED, 'User\'s password');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $output->writeln("<info>Email: $email, Password: $password</info>");
    }
}