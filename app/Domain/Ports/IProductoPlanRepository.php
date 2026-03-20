<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoPlanEntity;
use App\Domain\Entity\RespuestaEntity;

interface IProductoPlanRepository
{
    public function Create(ProductoPlanEntity $entity, string $lang): RespuestaEntity;
    public function Update(ProductoPlanEntity $entity, string $lang): RespuestaEntity;
    public function GetBySku(string $sku, string $lang): RespuestaEntity;
    public function GetById(int $id, string $lang): RespuestaEntity;

}
