<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DI\Service("intracto_secret_santa.pool_service")
 */
class PoolService
{
    /**
     * @DI\Inject("mailer")
     *
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("intracto_secret_santa.entry_shuffler")
     *
     * @var EntryShuffler $entryShuffler
     */
    public $entryShuffler;

    /**
     * @DI\Inject("templating")
     *
     * @var EngineInterface
     */
    public $templating;

    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

    /**
     * @DI\Inject("translator")
     *
     * @var TranslatorInterface;
     */
    public $translator;

    /**
     * @DI\Inject("router")
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public $routing;

    public function sendForgotManageLinkMail($email)
    {
        $results = $this->em->getRepository('IntractoSecretSantaBundle:Pool')->findAllAdminPools($email);

        if (count($results) == 0) {
            return false;
        }

        $poolLinks = array();
        foreach ($results as $result) {
            $text = $this->translator->trans('manage.title');

            if ($result['date'] instanceof \DateTime) {
                $text .= ' (' . $result['date']->format('d/m/Y') . ')';
            }

            $poolLinks[] = array(
                'url' => $this->routing->generate('pool_manage', array('listUrl' => $result['listurl']), Router::ABSOLUTE_URL),
                'text' => $text,
            );
        }

        $this->translator->setLocale($results[0]['locale']);

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails.forgot_link.subject'))
            ->setFrom($this->adminEmail, $this->translator->trans('emails.sender'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:forgotlink.txt.twig',
                    array('poolLinks' => $poolLinks)
                )
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:forgotlink.html.twig',
                    array('poolLinks' => $poolLinks)
                ),
                'text/html'
            );
        $this->mailer->send($message);

        return true;
    }
}
