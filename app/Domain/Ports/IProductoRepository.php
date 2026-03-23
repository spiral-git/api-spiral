<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\RespuestaEntity;

interface IProductoRepository
{
    public function Create(ProductoEntity $productoEntity, string $lang): RespuestaEntity;

    public function GetAll(string $lang, int $perPage, int $page, int $ownerId, string $filter): RespuestaEntity;

    public function GetById(int $id, string $lang): RespuestaEntity;

    public function Update(ProductoEntity $productoEntity, string $lang): RespuestaEntity;
}
