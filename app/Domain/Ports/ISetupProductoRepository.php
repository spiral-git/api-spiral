<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\RespuestaEntity;

interface ISetupProductoRepository
{
    public function Create(ProductoSetupEntity $entity, string $lang): RespuestaEntity; 
    public function GetById(int $id, string $lang): RespuestaEntity;
    
}
