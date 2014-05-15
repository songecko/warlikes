<?php

namespace Warlikes\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreateTablesCommand extends Command
{		
	protected function configure()
    {    	
        $this
            ->setName('database:tables:create')
            ->setDescription('Create all tables')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->write('creating tables...');
    	$container = $this->getApplication()->getContainer();
    	$conn = $container->get('database')->getConnection();
    	$platform = $conn->getDatabasePlatform();
    	
    	$schema = new \Doctrine\DBAL\Schema\Schema();
    	
    	//user table
    	$userTable = $schema->createTable("user");
    	$userTable->addColumn("id", "integer", array("unsigned" => true, "autoincrement" => true));
    	$userTable->addColumn("fbid", "string", array("length" => 255));
    	$userTable->addColumn("name", "string", array("length" => 255));
    	$userTable->addColumn("last_name", "string", array("length" => 255, "notnull" => false));
    	$userTable->addColumn("dni", "string", array("length" => 255));
    	$userTable->addColumn("email", "string", array("length" => 255));
    	$userTable->addColumn("birth_date", "date");
    	$userTable->addColumn("photo", "string", array("length" => 255));
    	$userTable->addColumn("votes", "integer", array("default" => 0));
    	$userTable->addColumn("has_cencosud", "boolean");
    	$userTable->addColumn("newsletter_easy", "boolean");
    	$userTable->addColumn("newsletter_cencosud", "boolean");
    	$userTable->setPrimaryKey(array("id"));
    	$userTable->addUniqueIndex(array("email"));
    	
    	//user_look table
    	$userVoteTable = $schema->createTable("user_vote");
    	$userVoteTable->addColumn("id", "integer", array("unsigned" => true, "autoincrement" => true));
    	$userVoteTable->addColumn("user_id", "integer", array("unsigned" => true));
    	$userVoteTable->addColumn("voted_user_id", "integer", array("unsigned" => true));
    	$userVoteTable->addColumn("ip", "string", array("length" => 255));
    	$userVoteTable->setPrimaryKey(array("id"));
    	$userVoteTable->addForeignKeyConstraint($userTable, array("user_id"), array("id"), array("onDelete" => "CASCADE"));
    	
    	$queries = $schema->toSql($platform); // get queries to create this schema.
    	foreach ($queries as $query)
    	{
    		$conn->query($query);
    	}
    	
    	$output->writeln('done.');
    }
}