<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class UploadsDoc
{
    #[OA\Post(
        path: '/uploads/upload',
        tags: ['Uploads'],
        summary: 'Subir imagen, video o pdf',
        
        parameters: [
            new OA\Parameter(
                name: 'dir',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                example: 'productos'
            ),
            new OA\Parameter(
                name: 'lang',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                example: 'es'
            ),
        ],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['file'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary'
                        )
                    ]
                )
            )
        ),

        responses: [
            new OA\Response(
                response: 201,
                description: 'Archivo subido correctamente'
            ),
            new OA\Response(
                response: 400,
                description: 'Error en la solicitud'
            )
        ]
    )]
    public function upload() {}
}