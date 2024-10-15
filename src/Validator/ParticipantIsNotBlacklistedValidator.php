<?php

namespace App\Validator;

use App\Entity\BlacklistEmail;
use App\Service\HashService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ParticipantIsNotBlacklistedValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;
    private HashService $hashService;

    public function __construct(
        EntityManagerInterface $em,
        HashService $hashService,
    ) {
        $this->em = $em;
        $this->hashService = $hashService;
    }

    /**
     * @param string $email
     */
    public function validate($email, Constraint $constraint): void
    {
        $email = $this->hashService->hashEmail($email);
        $repository = $this->em->getRepository(BlacklistEmail::class);
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
