<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TokenAuthEntity;

interface ITokenAuthRepository
{
    public function GetByToken(string $token, string $lang): RespuestaEntity;
    public function New(TokenAuthEntity $tokenAuth, string $lang): RespuestaEntity;
    public function Delete(string $token, string $lang): RespuestaEntity;
    public function DeleteAll(int $id_usuario, string $lang): RespuestaEntity;
}
