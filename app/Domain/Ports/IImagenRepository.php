<?php

namespace App\Domain\Ports;

use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\RespuestaEntity;

interface IImagenRepository
{
    public function Create(ImagenProductoEntity $entity, string $lang): RespuestaEntity;
    public function Update(ImagenProductoEntity $entity, string $lang): RespuestaEntity;
    public function GetByRuta(string $ruta, string $lang): RespuestaEntity;
    public function GetAllByProducto(int $id, string $lang): RespuestaEntity;
    public function Delete(int $id, string $lang): RespuestaEntity;
}
