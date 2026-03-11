<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TokenAuthEntity;
use App\Domain\Ports\ITokenAuthRepository;
use Exception;

class TokenAuthService
{
    protected ITokenAuthRepository $_tokenAuthRepository;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];

    public function __construct(ITokenAuthRepository $tokenAuthRepository)
    {
        $this->_tokenAuthRepository = $tokenAuthRepository;
    }

    public function New(int $userId, string $lang): RespuestaEntity
    {
        try {
            $tokenAuth = new TokenAuthEntity();
            $tokenAuth->IdUsuario = $userId;
            $tokenAuth->Token = $this->GenerarToken($userId);

            return $this->_tokenAuthRepository->New($tokenAuth, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Delete($token, string $lang): RespuestaEntity
    {
        try {
            return $this->_tokenAuthRepository->Delete($token, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function DeleteAll(int $userId, string $lang): RespuestaEntity
    {
        try {
            return $this->_tokenAuthRepository->DeleteAll($userId, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function ValidateToken(string $token, string $lang): RespuestaEntity
    {
        try {
            return $this->_tokenAuthRepository->GetByToken($token, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GenerarToken(int $userId): string
    {
        return $token = time() . bin2hex(random_bytes(16)) . $userId;
    }
}
