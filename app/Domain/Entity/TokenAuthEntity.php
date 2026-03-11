<?php

namespace App\Domain\Entity;

class TokenAuthEntity
{
   
    public int $Id;
    public int $IdUsuario;
    public string $Token;

    public function __construct(){}
    
}
