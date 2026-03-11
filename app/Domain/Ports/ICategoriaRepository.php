<?php

namespace App\Domain\Ports;

use App\Domain\Entity\CategoriaEntity;
use App\Domain\Entity\RespuestaEntity;

interface ICategoriaRepository
{
    public function Create(CategoriaEntity $entity, string $lang): RespuestaEntity;
    public function Update(CategoriaEntity $entity, string $lang): RespuestaEntity;
    public function GetAll(int $idLenguaje, string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;
}
