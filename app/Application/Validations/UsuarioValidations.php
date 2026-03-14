<?php

namespace App\Application\Validations;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Domain\Entity\RespuestaEntity;

class UsuarioValidations
{
    private static array $translations = [
        "es" => [
            "validation_success" => "Validación correcta",
            "validation_error" => "Errores de validación",

            "name_required" => "Los nombres son obligatorios",
            "lastname_required" => "Los apellidos son obligatorios",

            "email_required" => "El correo es obligatorio",
            "email_invalid" => "Formato de correo inválido",

            "password_required" => "La contraseña es obligatoria",
            "password_min" => "Debe tener mínimo 8 caracteres",
            "password_upper" => "Debe contener al menos una mayúscula",
            "password_lower" => "Debe contener al menos una minúscula",
            "password_number" => "Debe contener al menos un número",

            "phone_required" => "El teléfono obligatorio",
            "phone_invalid" => "Formato de teléfono inválido",
        ],
        "en" => [
            "validation_success" => "Validation successful",
            "validation_error" => "Validation errors",

            "name_required" => "First name is required",
            "lastname_required" => "Last name is required",

            "email_required" => "Email is required",
            "email_invalid" => "Invalid email format",

            "password_required" => "Password is required",
            "password_min" => "Must contain at least 8 characters",
            "password_upper" => "Must contain at least one uppercase letter",
            "password_lower" => "Must contain at least one lowercase letter",
            "password_number" => "Must contain at least one number",

            "phone_required" => "Phone is required",
            "phone_invalid" => "Invalid phone format",
        ]
    ];
    public static function validar(UsuarioInputDto $dto, string $lang): RespuestaEntity
    {
        $errores = [];

        self::validarNombres($dto->Nombres, $errores, $lang);
        self::validarApellidos($dto->Apellidos, $errores, $lang);
        self::validarCorreo($dto->Correo, $errores, $lang);
        self::validarPassword($dto->Password, $errores, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarNombres(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["nombres"] = self::$translations[$lang]['name_required'];
            return;
        }

    }

    private static function validarApellidos(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["apellidos"] = self::$translations[$lang]['lastname_required'];
            return;
        }

    }

    private static function validarCorreo(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["correo"] = self::$translations[$lang]['email_required'];
            return;
        }

        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $errores["correo"] = self::$translations[$lang]['email_invalid'];
        }

    }

    private static function validarPassword(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["password"] = self::$translations[$lang]['password_required'];
            
        }

        if (strlen($valor) < 8) {
            $errores["password"] = self::$translations[$lang]['password_min'];
            
        }

        if (!preg_match('/[A-Z]/', $valor)) {
            $errores["password"] = self::$translations[$lang]['password_upper'];
        }

        if (!preg_match('/[a-z]/', $valor)) {
            $errores["password"] = self::$translations[$lang]['password_lower'];
        }

        if (!preg_match('/[0-9]/', $valor)) {
            $errores["password"] = self::$translations[$lang]['password_number'];
        }
    }

}
