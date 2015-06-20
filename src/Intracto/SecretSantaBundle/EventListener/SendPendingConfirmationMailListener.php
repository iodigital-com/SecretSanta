<?php

namespace Intracto\SecretSantaBundle\EventListener;

use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Event\PoolEvent;
use Intracto\SecretSantaBundle\Event\PoolEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\DataCollectorTranslator;

class SendPendingConfirmationMailListener implements EventSubscriberInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $adminEmail;
    /**
     * @var DataCollectorTranslator
     */
    private $translator;

    public static function getSubscribedEvents()
    {
        return array(
            PoolEvents::NEW_POOL_CREATED => 'onNewPool'
        );
    }

    public function __construct(EngineInterface $templating, \Swift_Mailer $mailer, $adminEmail, DataCollectorTranslator $translator)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->translator = $translator;

    }

    public function onNewPool(PoolEvent $event)
    {
        $this->sendPendingConfirmationMail($event->getPool());
    }

    private function sendPendingConfirmationMail(Pool $pool)
    {
        $this->translator->setLocale($pool->getLocale());

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails.pendingconfirmation.subject'))
            ->setFrom($this->adminEmail, $this->translator->trans('emails.sender'))
            ->setTo($pool->getOwnerEmail())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingconfirmation.txt.twig',
                    array('pool' => $pool)
                )
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingconfirmation.html.twig',
                    array('pool' => $pool)
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
