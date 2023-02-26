<?php

namespace App\Validator;

use App\Repository\ShowRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CreneauHoraireValidator extends ConstraintValidator
{

    private ShowRepository $showRepository;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->showRepository = new ShowRepository($entityManager);
    }


    public function validate(mixed $value, Constraint $constraint)
    {
        if(!$constraint instanceof CreneauHoraire) {
            throw new UnexpectedValueException($constraint, CreneauHoraire::class);
        }

        $result = $this->showRepository->isSameCreneauHoraire($value);

        if(!$result) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->format('Y-m-d H:i:s'))
                ->addViolation();
        }

        return $result;
    }
}