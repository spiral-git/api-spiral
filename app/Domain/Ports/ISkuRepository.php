<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\SkuProductoEntity;

interface ISkuRepository
{
    public function Create(SkuProductoEntity $entity, string $lang): RespuestaEntity;
    public function GetBySku(string $sku, string $lang): RespuestaEntity;
    public function GetByProducto(int $idProducto, string $lang): RespuestaEntity;
    public function GetAllByProducto(int $idProducto, string $lang): RespuestaEntity;

    
}
