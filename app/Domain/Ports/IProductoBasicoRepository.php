<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoBasicoEntity;
use App\Domain\Entity\RespuestaEntity;

interface IProductoBasicoRepository
{
    public function Create(ProductoBasicoEntity $entity, string $lang): RespuestaEntity;
    public function Update(ProductoBasicoEntity $entity, string $lang): RespuestaEntity;
    public function GetBySku(string $sku, string $lang): RespuestaEntity;

}
