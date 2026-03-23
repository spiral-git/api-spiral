<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class UsuarioDocs
{
    // LOGIN------------------------------------------------------------------
    #[OA\Post(
        path: "/usuario/login",
        tags: ["Usuario"],
        summary: "Inicio de sesión",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["correo", "password", "lang"],
                properties: [
                    new OA\Property(property: "correo", type: "string", example: ""),
                    new OA\Property(property: "password", type: "string", example: ""),
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login exitoso"
            )
        ]
    )]
    public function login()
    {
    }

    // LOGOUT------------------------------------------------------------------
    #[OA\Post(
        path: "/usuario/loguot",
        tags: ["Usuario"],
        summary: "Cerrar Sesión",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["lang"],
                properties: [
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Sesión finalizada"
            )
        ]
    )]
    public function logout()
    {
    }

    // LOGOUTALL------------------------------------------------------------------
    #[OA\Post(
        path: "/usuario/logout-all",
        tags: ["Usuario"],
        summary: "Cerrar Sesiones",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["lang"],
                properties: [
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Sesiones finalizada"
            )
        ]
    )]
    public function logout_all()
    {
    }

    // CREATED------------------------------------------------------------------
    #[OA\Post(
        path: "/usuario/create",
        tags: ["Usuario"],
        summary: "Crear Usuario",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["lang", "nombres", "apellidos", "correo", "password", "telefono", "imagen"],
                properties: [
                    new OA\Property(property: "lang", type: "string", example: "es"),
                    new OA\Property(property: "nombres", type: "string", example: ""),
                    new OA\Property(property: "apellidos", type: "string", example: ""),
                    new OA\Property(property: "correo", type: "string", example: ""),
                    new OA\Property(property: "password", type: "string", example: ""),
                    new OA\Property(property: "telefono", type: "string", example: ""),
                    new OA\Property(property: "imagen", type: "string", example: ""),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Usuario creado"
            )
        ]
    )]
    public function created()
    {
    }

    // CREATED SOCIO------------------------------------------------------------------
    #[OA\Post(
        path: "/usuario/create-socio",
        tags: ["Usuario"],
        summary: "Crear Socio",
         security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["lang", "nombres", "apellidos", "correo", "password", "telefono", "imagen"],
                properties: [
                    new OA\Property(property: "lang", type: "string", example: "es"),
                    new OA\Property(property: "nombres", type: "string", example: ""),
                    new OA\Property(property: "apellidos", type: "string", example: ""),
                    new OA\Property(property: "correo", type: "string", example: ""),
                    new OA\Property(property: "password", type: "string", example: ""),
                    new OA\Property(property: "telefono", type: "string", example: ""),
                    new OA\Property(property: "imagen", type: "string", example: ""),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Usuario creado"
            )
        ]
    )]
    public function created_socio()
    {
    }


}
