<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoRepository implements IProductoRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "product_created" => "Producto creado correctamente",
            "list_retrieved" => "Lista obtenida correctamente",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "product_created" => "Product created successfully",
            "list_retrieved" => "List retrieved successfully",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PRODUCTOS";
    }

    public function Create(ProductoEntity $productoEntity, string $lang): RespuestaEntity
    {
        try {

          
                $id = DB::table($this->table)->insertGetId([
                    'id_tipo_producto' => $productoEntity->IdTipoProducto,
                    'id_tipo_pago' => $productoEntity->IdTipoPago,
                    'id_lenguaje' => $productoEntity->IdLenguaje,
                    'nombre' => $productoEntity->Nombre,
                    'descripcion' => $productoEntity->Descripcion,
                    'valoracion_general' => $productoEntity->ValoracionGeneral,
                    'created_at' => $productoEntity->CreateAt,
                    'status' => $productoEntity->Status
                ]);

                $productoEntity->Id = $id;

                return new RespuestaEntity(
                    $this->translations[$lang]['product_created'] ?? "",
                    true,
                    $productoEntity
                );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetAll(string $lang, string $pais): RespuestaEntity
    {
        try {

            $rows = DB::table(DB::raw('"PRODUCTOS" as p'))
                ->join(DB::raw('"TIPO_PRODUCTO" as tproducto'), 'p.id_tipo_producto', '=', 'tproducto.id')
                ->join(DB::raw('"TIPO_PAGO" as tpago'), 'p.id_tipo_pago', '=', 'tpago.id')
                ->join(DB::raw('"LENGUAJE" as lenguaje'), 'p.id_lenguaje', '=', 'lenguaje.id')

                ->where('p.status', true)
                ->where('pais.nombre', $pais)
                ->where('lenguaje.nombre', $lang)

                ->select(
                    'p.id as producto_id',
                    'p.id_tipo_producto as id_tipo_producto',
                    'p.id_tipo_pago as id_tipo_pago',
                    'p.id_producto_setup as id_producto_setup',
                    'p.id_lenguaje as id_lenguaje',
                    'p.nombre as nombre_producto',
                    'p.descripcion as descripcion_producto',
                    'p.valoracion_general as valoracion_general',
                    'p.status as status',
                    'p.created_at as created_at',

                    'tproducto.nombre as tipo_producto',
                    'tpago.nombre as tipo_pago',
                    'lenguaje.nombre as lenguaje'
                )

                ->orderBy('p.nombre')
                ->distinct()
                ->get();

            $producto = $rows->map(function ($row) {

                $producto = new ProductoEntity();
                $producto->Id = $row->producto_id;
                $producto->Nombre = $row->nombre_producto;
                $producto->Descripcion = $row->descripcion_producto;
                $producto->ValoracionGeneral = $row->valoracion_general;
                $producto->CreateAt = $row->created_at;
                $producto->IdLenguaje = $row->id_lenguaje;
                $producto->IdTipoPago = $row->id_tipo_pago;
                $producto->IdTipoProducto = $row->id_tipo_producto;

                return $producto;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? "",
                true,
                $producto
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByName(string $name, string $lang): RespuestaEntity
    {
        return new RespuestaEntity(
            $this->translations[$lang]['error'] ?? "",
            false,
            null
        );
    }

    public function GetById(int $id, string $lang): RespuestaEntity
    {
        return new RespuestaEntity(
            $this->translations[$lang]['error'] ?? "",
            false,
            null
        );
    }

    public function Update(ProductoEntity $productoEntity, string $lang): RespuestaEntity
    {
        return new RespuestaEntity(
            $this->translations[$lang]['error'] ?? "",
            false,
            null
        );
    }
}