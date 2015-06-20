<?php

namespace Intracto\SecretSantaBundle\Mailer;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Mailer\Mail\BatchEntryMail;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;

/**
 * Responsible for sending all emails.
 */
class Mailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Swift_Transport
     */
    private $transport;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Swift_Transport $transport
     * @param EntityManager $em
     * @param Translator $translator
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_Transport $transport, EntityManager $em, Translator $translator, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->transport = $transport;
        $this->em = $em;
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * @param $fromEmail
     * @param BatchEntryMail[] $batchMails
     * @param bool $doSend : if false, nothing is sent
     */
    public function sendBatchMails($fromEmail, $batchMails, $doSend = true)
    {
        foreach ($batchMails as $batchMail) {
            $this->sendBatchMail($fromEmail, $batchMail, $doSend);
        }
    }

    /**
     * @param $fromEmail
     * @param BatchEntryMail $batchMail
     * @param $doSend
     */
    private function sendBatchMail($fromEmail, BatchEntryMail $batchMail, $doSend)
    {
        $receivers = $batchMail->getReceiverEntries($this->em);

        $this->writeOutput('Sending "' . $batchMail->getName() . '" mail to ' . count($receivers) . ' people from ' . $fromEmail);

        foreach ($receivers as $receiver) {
            $this->writeOutput(' -> ' . $receiver->getEmail());

            $htmlTemplate = $batchMail->getHtmlTemplate($receiver);
            $plainTextTemplate = $batchMail->getPlainTextTemplate($receiver);
            $templateData = $batchMail->getTemplateData($receiver, $this->em);

            $this->translator->setLocale($receiver->getPool()->getLocale());

            $plainTextBody = $this->twig->render($plainTextTemplate, $templateData);
            $htmlBody = $this->twig->render($htmlTemplate, $templateData);

            $message = \Swift_Message::newInstance()
                ->setSubject($batchMail->getSubject($receiver, $this->translator))
                ->setFrom($fromEmail, $batchMail->getFrom($receiver, $this->translator))
                ->setTo($receiver->getEmail())
                ->setBody($htmlBody)
                ->addPart($plainTextBody);

            if ($doSend) {
                $this->mailer->send($message);
                $this->transport->getSpool()->flushQueue($this->transport);

                $batchMail->handleMailSent($receiver, $this->em);
            }
        }
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @param $message
     */
    private function writeOutput($message)
    {
        if ($this->output !== null) {
            $this->output->writeln($message);
        }
    }

}