<?php

namespace Intracto\SecretSantaBundle\Twig;

use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Translation\TranslatorInterface;

class MailStatusExtension extends \Twig_Extension
{
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('mailstatus', [$this, 'mailstatusFilter']),
        ];
    }

    public function mailstatusFilter(Participant $participant)
    {
        if ($participant->getParty()->getCreated()) {
            switch (true) {
                case $participant->getViewdate() != null:
                    $status = $this->translator->trans('mail_status_extension.viewed');
                    $icon = 'fa-check';
                    $type = 'success';

                    break;
                case $participant->getOpenEmailDate() != null:
                    $status = $this->translator->trans('mail_status_extension.opened');
                    $icon = 'fa-eye';
                    $type = 'warning';

                    break;
                case $participant->getEmailDidBounce():
                    $status = $this->translator->trans('mail_status_extension.bounced');
                    $icon = 'fa-exclamation-triangle';
                    $type = 'danger';

                    break;
                default:
                    $status = $this->translator->trans('mail_status_extension.unknown');
                    $icon = 'fa-question';
                    $type = 'muted';
            }
        } else {
            $status = $this->translator->trans('mail_status_extension.not_started');
            $icon = 'fa-info';
            $type = 'muted';
        }

        return '<span class="text-'.$type.'"><i class="fa '.$icon.'" aria-hidden="true"></i> '.$status.'</span>';
    }
}
