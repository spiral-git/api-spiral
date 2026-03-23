<?php

namespace App\Domain\Ports;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\RespuestaEntity;

interface ICategoriaProductoRepository
{
    public function Create(CategoriaProductoEntity $entity, string $lang): RespuestaEntity;
    public function Update(CategoriaProductoEntity $entity, string $lang): RespuestaEntity;
    public function ExistCategoriaProducto(int $idCategoria, string $idProducto, string $lang): RespuestaEntity;
    public function GetAllByProducto(int $idProdcuto, string $lang): RespuestaEntity;

}
