<?php

namespace Intracto\SecretSantaBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Service\HashService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ParticipantIsNotBlacklistedValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;
    private HashService $hashService;

    public function __construct(
        EntityManagerInterface $em,
        HashService $hashService
    ) {
        $this->em = $em;
        $this->hashService = $hashService;
    }

    public function validate(string $email, Constraint $constraint)
    {
        $email = $this->hashService->hashEmail($email);
        $repository = $this->em->getRepository('IntractoSecretSantaBundle:BlacklistEmail');
        $results = $repository->createQueryBuilder('b')
            ->where('b.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
        if (count($results) > 0) {
            $this->context->buildViolation('participant.blacklisted')->addViolation();
        }
    }
}
