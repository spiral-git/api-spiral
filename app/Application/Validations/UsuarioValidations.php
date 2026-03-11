<?php

namespace App\Application\Validations;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Domain\Entity\RespuestaEntity;

class UsuarioValidations
{
    public static function validar(UsuarioInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarNombres($dto->Nombres, $errores);
        self::validarApellidos($dto->Apellidos, $errores);
        self::validarCorreo($dto->Correo, $errores);
        self::validarPassword($dto->Password, $errores);
        self::validarTelefono($dto->Telefono, $errores);

        return new RespuestaEntity(
            empty($errores) ? "Validación correcta" : "Errores de validación",
            empty($errores),
            $errores
        );
    }

    private static function validarNombres(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["nombres"] = "Los nombres son obligatorios";
            return;
        }

        if (strlen($valor) > 100) {
            $errores["nombres"] = "Los nombres no pueden superar 100 caracteres";
        }
    }

    private static function validarApellidos(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["apellidos"] = "Los apellidos son obligatorios";
            return;
        }

        if (strlen($valor) > 100) {
            $errores["apellidos"] = "Los apellidos no pueden superar 100 caracteres";
        }
    }

    private static function validarCorreo(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["correo"] = "El correo es obligatorio";
            return;
        }

        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $errores["correo"] = "Formato de correo inválido";
        }
    }

    private static function validarPassword(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["password"] = "La contraseña es obligatoria";
            return;
        }

        if (strlen($valor) < 8) {
            $errores["password"] = "Debe tener mínimo 8 caracteres";
            return;
        }

        if (!preg_match('/[A-Z]/', $valor)) {
            $errores["password"] = "Debe contener al menos una mayúscula";
        }

        if (!preg_match('/[a-z]/', $valor)) {
            $errores["password"] = "Debe contener al menos una minúscula";
        }

        if (!preg_match('/[0-9]/', $valor)) {
            $errores["password"] = "Debe contener al menos un número";
        }
    }

    private static function validarTelefono(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            return;
        }

        if (!preg_match('/^[0-9+\-\s]{7,20}$/', $valor)) {
            $errores["telefono"] = "Formato de teléfono inválido";
        }
    }
}
