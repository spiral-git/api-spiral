<?php

namespace App\Domain\Entity;

class ProductoSetupEntity
{
    public int $Id;
    public int $IdTipoSetup;
    public int $Amount;

    public function __construct(){}
}
