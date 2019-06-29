<?php

namespace App\Command;

use Gaufrette\StreamWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Node\Expression\Test\SameasTest;

class TestGaufretteCommand extends Command
{
    protected static $defaultName = 'app:test:gaufrette';

    private $container;

    public function __construct(ContainerInterface $container, string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $photofs = $this->container->get('knp_gaufrette.filesystem_map')->get('photos');
        $downloadfs = $this->container->get('knp_gaufrette.filesystem_map')->get('downloads');

        /*$wrapper = StreamWrapper::getFilesystemMap();
        $wrapper->set('photos', $photofs);
        $wrapper->set('downloads', $downloadfs);
        StreamWrapper::register();*/

        /*foreach ($photofs->listKeys()['keys'] as $photo)
        {
            copy("gaufrette://photos/".$photo, "gaufrette://downloads/".$photo);
            //$photofs->delete($photo);
            //$photoFile = $photofs->get($photo);


        }*/
        dump($photofs->listKeys());

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
