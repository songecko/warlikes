<?php

namespace Urb\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDropTablesCommand extends Command
{		
	protected function configure()
    {    	
        $this
            ->setName('database:tables:drop')
            ->setDescription('Drop all tables')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->write('dropping tables...');
    	$container = $this->getApplication()->getContainer();
    	$conn = $container->get('database')->getConnection();
    	$platform = $conn->getDatabasePlatform();
    	
    	$schema = new \Doctrine\DBAL\Schema\Schema();
    	
    	$userLookTable = $schema->createTable("user_look");
    	$userTable = $schema->createTable("user");
    	$queries = $schema->toDropSql($platform); // get queries to safely delete this schema.
    	
    	foreach ($queries as $query)
    	{
    		$conn->query($query);
    	}
        
    	$output->writeln('done.');
    }
}