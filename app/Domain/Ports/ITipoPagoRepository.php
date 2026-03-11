<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoPagoEntity;

interface ITipoPagoRepository
{
    public function Create(TipoPagoEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
}
