<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:cron')
            ->setDescription('Send orders that have not been sent yet.')
            ->setHelp('This command will get all the orders that have not been sent and send an email to mailer_to from parameters.yml');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln('Launching cronjob for justorderit…');
        }

        $controller = $this->getContainer()->get('AppBundle\Controller\CronController');
        $result = $controller->cronAction();

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln("Orders sent: $result");
        }

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln('kthxbye :)');
        }
    }
}
