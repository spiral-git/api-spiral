<?php

namespace App\Domain\Ports;

use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\RespuestaEntity;

interface IPaisProductoRepository
{
    public function Create(PaisProductoEntity $entity, string $lang): RespuestaEntity;
    public function GetAllBySku(string $sku, string $lang): RespuestaEntity;
    public function ExistPaisProducto(int $idPais, string $sku, string $lang): RespuestaEntity;
    public function Update(PaisProductoEntity $entity, string $lang): RespuestaEntity;
    public function Delete(string $sku, int $idPais, string $lang): RespuestaEntity;
}
 