<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoSetupEntity;

interface ITipoSetupRepository
{ 
    public function Create(TipoSetupEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
}
 