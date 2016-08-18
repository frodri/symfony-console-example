<?php
namespace SymfonyExample\Commands;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkScannerCommand extends Command
{
    protected function configure()
    {
        $this->setName('scan-links');
        $this->setDescription('Scans links within the provided url');

        $this->addArgument('url', InputArgument::REQUIRED, 'Url to scan');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $crawler = $client->request('GET', $input->getArgument('url'));
        // This XPath query will only filter text-only 'a' tags from the url.
        // That means no paragraphs, no spans, no images, no icon font tags,
        // no nothin'. Just text.
        $crawler
            ->filterXPath('//a[not(*)]')
            ->each(function ($node) use ($output) {
                $output->writeln($node->text() .' ['.$node->link()->getURI(). ']');
            });
    }
}