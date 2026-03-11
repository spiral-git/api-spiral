<?php

namespace App\Domain\Ports;

use App\Domain\Entity\PaisEntity;
use App\Domain\Entity\RespuestaEntity;

interface IPaisRepository
{
    public function Create(PaisEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
}
