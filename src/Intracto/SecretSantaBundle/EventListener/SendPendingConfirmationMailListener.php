<?php

namespace Intracto\SecretSantaBundle\EventListener;

use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Event\PoolEvent;
use Intracto\SecretSantaBundle\Event\PoolEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;

class SendPendingConfirmationMailListener implements EventSubscriberInterface
{
    private $templating;
    private $mailer;
    private $adminEmail;

    public static function getSubscribedEvents()
    {
        return array(
            PoolEvents::NEW_POOL_CREATED => 'onNewPool'
        );
    }

    public function __construct(EngineInterface $templating, \Swift_Mailer $mailer, $adminEmail)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function onNewPool(PoolEvent $event)
    {
        $this->sendPendingConfirmationMail($event->getPool());
    }

    private function sendPendingConfirmationMail(Pool $pool)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Secret Santa Validation')
            ->setFrom($this->adminEmail, "Santa Claus")
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
