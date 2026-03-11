<?php

namespace App\Domain\Entity;

class DetallePlanEntity
{
    public int $Id;
    public string $Detalle;
    public int $IdProductoPlan;

    public function __construct(){}
}
