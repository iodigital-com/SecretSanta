<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class ParticipantIsNotBlacklistedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string     $email
     * @param Constraint $constraint
     */
    public function validate($email, Constraint $constraint)
    {
        $repository = $this->em->getRepository('IntractoSecretSantaBundle:BlacklistEmail');
        $results = $repository->createQueryBuilder('b')
            ->where('b.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
        if (count($results) > 0) {
            $this->context->buildViolation('entry.blacklisted')->addViolation();
        }
    }
}
