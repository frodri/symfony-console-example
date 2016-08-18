<?php
namespace SymfonyExample\Commands;

use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscriberCommand extends Command
{
    private $subscribersList = array();
    
    protected function configure()
    {
        $this->setName('process-subscribers');
        $this->setDescription('Removes bounced emails and unsubscribed members from subscriber list');

        $this->addArgument('subscribers', InputArgument::REQUIRED, 'Text file containing the subscribers list');
        $this->addArgument('unsubscribed', InputArgument::REQUIRED, 'Text file containing the unsubscribed users');
        $this->addArgument('bounced', InputArgument::REQUIRED, 'Text file containing the users with bounced emails');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->editSubscribers($input->getArgument('subscribers'), 'add');
        $this->editSubscribers($input->getArgument('bounced'), 'remove');
        $this->editSubscribers($input->getArgument('unsubscribed'), 'remove');

        $output->writeln(array_keys($this->subscribersList));
    }

    private function editSubscribers($fileLocation, $action) {
        $file = new SplFileObject($fileLocation);

        while (!$file->eof()) {
            // Trimming white space from emails, because it gets in the way.
            // Lowercasing the emails because a lot of email providers consider
            // emails to be case-insensitive despite of what the RFCs say.
            $email = trim(strtolower($file->fgets()));

            // Simple email filter to avoid storing/unsetting emails we don't need to.
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                switch ($action) {
                    case 'add':
                        // By assigning emails to an array key,
                        // we avoid having to loop through the subscribers
                        // array in order to remove an email from it -
                        // we can use a simple unset call instead.
                        $this->subscribersList[$email] = TRUE;
                        
                        break;
                    case 'remove':
                        unset($this->subscribersList[$email]);
                        break;
                }
            }
            
        }

        // Destucting the file object myself due to paranoia.
        $file = null;
    }

}