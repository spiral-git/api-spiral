<?php

namespace App\Domain\Ports;

use App\Domain\Entity\LenguajeEntity;
use App\Domain\Entity\RespuestaEntity;

interface ILenguajeRepository
{
    public function Create(LenguajeEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
}
