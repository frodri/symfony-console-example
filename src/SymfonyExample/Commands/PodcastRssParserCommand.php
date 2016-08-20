<?php
namespace SymfonyExample\Commands;

use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PodcastRssParserCommand extends Command
{
    protected function configure()
    {
        $this->setName('podcast-rss-parser');
        $this->setDescription('Fetches the names and descriptions of up to 20 items in a broadcast feed');

        $this->addArgument('url', InputArgument::REQUIRED, 'Url to scan');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $current = 0;
        $max = 20;
        
        $rss = file_get_contents($input->getArgument('url'));
        $xml_element = new SimpleXMLElement($rss);

        foreach($xml_element->item as $item) {
            if ($current < $max) {
                $output->writeln((string)$item->title);
                $output->writeln('--------------------------------------------------------');
                $output->writeln(strip_tags((string)$item->description));
                $output->writeln('');
                $current++;
            } 
        }
    }
}