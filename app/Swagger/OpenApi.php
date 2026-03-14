<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Spiral Company API',
    version: '1.0.0',
    description: 'Documentación de la API de Spiral Company'
)]
#[OA\Server(
    url: 'https://api.spiralcompanypr.com/api',
    description: 'Api Principal Spiral Company'
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApi {}