<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\RespuestaEntity;

interface IProductoVarianteRepository
{
    public function Create(ProductoVarianteEntity $entity, string $lang): RespuestaEntity;
    public function Update(ProductoVarianteEntity $entity, string $lang): RespuestaEntity;
    public function GetBySku(string $sku, string $lang): RespuestaEntity;

}
