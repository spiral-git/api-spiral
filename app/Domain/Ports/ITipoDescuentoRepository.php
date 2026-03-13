<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoDescuentoEntity;

interface ITipoDescuentoRepository
{
     public function Create(TipoDescuentoEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
    public function GetById(int $id, string $lang): RespuestaEntity;
}
