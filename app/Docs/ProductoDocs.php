<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class ProductoDocs
{

    //CREATED PRODUCTO BASE------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-base",
        tags: ["Producto"],
        summary: "Crea producto base",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idTipoProducto", "idTipoPago", "idLenguaje", "nombre", "descripcion", "lang"],
                properties: [
                    new OA\Property(property: "idTipoProducto", type: "integer", example: 0),
                    new OA\Property(property: "idTipoPago", type: "integer", example: 0),
                    new OA\Property(property: "idLenguaje", type: "integer", example: 0),
                    new OA\Property(property: "nombre", type: "string", example: ""),
                    new OA\Property(property: "descripcion", type: "string", example: ""),
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "producto base creado correctamente"
            )
        ]
    )]
    public function created_producto_base()
    {
    }

    //CREATED PRODUCTO IMAGENES------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-imagen",
        tags: ["Producto"],
        summary: "Añadir imagen al producto",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "imagen", "lang"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "imagen",
                        type: "string",
                        example: ""
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Imagen del producto asociada correctamente"
            )
        ]
    )]
    public function created_producto_imagenes()
    {
    }

    //CREATED PRODUCTO PAISES------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-pais",
        tags: ["Producto"],
        summary: "Añadir pais en que esta disponible el producto",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["sku", "idPais", "lang"],
                properties: [
                    new OA\Property(
                        property: "sku",
                        type: "string",
                        example: ""
                    ),
                    new OA\Property(
                        property: "idPais",
                        type: "integer", 
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Pais asociado al producto correctamente"
            )
        ]
    )]
    public function created_producto_paises()
    {
    }

    //CREATED PRODUCTO CATEGORIAS------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-categoria",
        tags: ["Producto"],
        summary: "Añadir categoria asociada al producto",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "idCategoria", "lang"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idCategoria",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Categoria asociada al producto correctamente"
            )
        ]
    )]
    public function created_producto_categoria()
    {
    }


    //CREATED PRODUCTO COTIZABLE------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-cotizacion",
        tags: ["Producto"],
        summary: "Crear producto cotizable",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "maximoRecursos", "idTipoSetup", "amountSetup", "lang"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "maximoRecursos",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "amountSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Producto cotizable creado correctamente"
            )
        ]
    )]
    public function created_producto_cotizacion()
    {
    }


    //CREATED PRODUCTO BASICO------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-basico",
        tags: ["Producto"],
        summary: "Crear producto basico",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "maximoRecursos", "idTipoSetup", "amountSetup", "lang"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "maximoRecursos",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "amountSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "precio",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoDescuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "Descuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Producto basico creado correctamente"
            )
        ]
    )]
    public function created_producto_basico()
    {
    }


    

    //CREATED PRODUCTO VARIANTE------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-variante",
        tags: ["Producto"],
        summary: "Crear producto variante",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "maximoRecursos", "idTipoSetup", "amountSetup", "lang", "nombre"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "maximoRecursos",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "amountSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "precio",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoDescuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "descuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    ),

                    new OA\Property(
                        property: "nombre",
                        type: "string",
                        example: ""
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Producto variante creado correctamente"
            )
        ]
    )]
    public function created_producto_variante()
    {
    }


     //CREATED PRODUCTO PLAN------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-plan",
        tags: ["Producto"],
        summary: "Crear producto plan",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "maximoRecursos", "idTipoSetup", "amountSetup", "lang", "nombre", "descripcion", "etiqueta"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "maximoRecursos",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "amountSetup",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "precio",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "idTipoDescuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "descuento",
                        type: "integer",
                        example: 0
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    ),

                    new OA\Property(
                        property: "nombre",
                        type: "string",
                        example: ""
                    ),

                    new OA\Property(
                        property: "descripcion",
                        type: "string",
                        example: ""
                    ),

                    new OA\Property(
                        property: "etiqueta",
                        type: "string",
                        example: ""
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Producto plan creado correctamente"
            )
        ]
    )]
    public function created_producto_plan()
    {
    }

}
