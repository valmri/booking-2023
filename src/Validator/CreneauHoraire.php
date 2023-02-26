<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CreneauHoraire extends Constraint
{
    public string $message = "Veuillez choisir un nouveau créneau horaire, un spectacle est déjà planifié.";
    public string $mode = 'strict';

}