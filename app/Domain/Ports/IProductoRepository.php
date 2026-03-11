<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\RespuestaEntity;

interface IProductoRepository
{
    public function Create(ProductoEntity $productoEntity, string $lang): RespuestaEntity;
   public function GetAll(string $lang, string $pais): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
    public function GetById(int $id, string $lang): RespuestaEntity;
    public function Update(ProductoEntity $productoEntity, string $lang): RespuestaEntity;

}
