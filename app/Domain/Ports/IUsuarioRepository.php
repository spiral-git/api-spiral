<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\UsuarioEntity;

interface IUsuarioRepository
{
    public function Create(UsuarioEntity $usuario, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByCorreo(string $correo, string $lang): RespuestaEntity;
    public function GetById(int $id, string $lang): RespuestaEntity;

    // public function Update(string $correo): RespuestaEntity;

}
