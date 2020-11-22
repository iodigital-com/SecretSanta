<?php

namespace App\Tests\Behat;

use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

class EmailContext extends RawMinkContext implements ContainerAwareInterface
{
    use ContainerAwareContextTrait;

    /**
     * We need to purge the spool between each scenario.
     *
     * @BeforeScenario
     */
    public function purgeSpool()
    {
        if (!file_exists($this->getSpoolDir()) && !is_dir($this->getSpoolDir())) {
            return;
        }

        $filesystem = new Filesystem();
        $finder = $this->getSpooledEmails();

        /** @var File $file */
        foreach ($finder as $file) {
            $filesystem->remove($file->getRealPath());
        }
    }

    /**
     * @return Finder
     */
    public function getSpooledEmails()
    {
        $finder = new Finder();
        $spoolDir = $this->getSpoolDir();
        $finder->files()->in($spoolDir);

        return $finder;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function getEmailContent($file)
    {
        return unserialize(file_get_contents($file));
    }

    /**
     * @return string
     */
    protected function getSpoolDir()
    {
        return $this->getContainer()->getParameter('swiftmailer.spool.default.file.path');
    }

    /**
     * @Then /^the (?P<expectedSubject>[^"]+) mail should be sent to (?P<email>[^"]+)$/
     *
     * @throws \Exception
     */
    public function theMailShouldBeSentTo($expectedSubject, $email)
    {
        // Sleep for 1 second so the application has time to write the email to disk
        sleep(1);

        $spoolDir = $this->getSpoolDir();
        $filesystem = new Filesystem();
        if ($filesystem->exists($spoolDir)) {
            $finder = new Finder();

            // find every files inside the spool dir except hidden files
            $finder
                ->in($spoolDir)
                ->ignoreDotFiles(true)
                ->files();

            if ($finder->count() === 0) {
                throw new \LogicException(sprintf('No emails were sent'));
            }

            foreach ($finder as $file) {
                /** @var \Swift_Message $message */
                $message = $this->getEmailContent($file);

                // check the recipients
                $recipients = array_keys($message->getTo());
                if (!in_array($email, $recipients, true)) {
                    continue;
                }
                // check if this is the correct message type
                $headers = $message->getHeaders();

                if ($headers->has('subject')) {
                    $subject = $headers->get('subject')->getFieldBody();

                    if ($subject == $expectedSubject) {
                        return;
                    }
                }
            }
        } else {
            throw new \LogicException('The spool folder could not be opened');
        }

        throw new \LogicException(sprintf('The "%s" was not sent', $expectedSubject));
    }

    /**
     * TODO: check how to implement double rules (the second @given is not recognized correclty in a feature).
     *
     * @Given /^there should have been (\d+) send emails$/
     * @Given /^there should have been (\d+) send email$/
     */
    public function thereShouldHaveBeenSendEmails($nrOfEmails)
    {
        Assert::eq($this->getSpooledEmails()->count(), $nrOfEmails, 'Not all or no emails have been send');
    }
}
