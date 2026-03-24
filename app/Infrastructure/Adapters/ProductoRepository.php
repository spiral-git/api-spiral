<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoRepository;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoRepository implements IProductoRepository
{
    public string $table;

    private array $translations = [

        'es' => [
            'product_created' => 'Producto creado correctamente',
            'list_retrieved' => 'Lista obtenida correctamente',
            'error' => 'Ocurrió un error',
            'product_found' => 'Producto encontrado',
            'product_not_found' => 'Producto no encontrado',
        ],

        'en' => [
            'product_created' => 'Product created successfully',
            'list_retrieved' => 'List retrieved successfully',
            'error' => 'An error occurred',
            'product_found' => 'Product found',
            'product_not_found' => 'Product not found',
        ],

    ];

    public function __construct()
    {
        $this->table = 'PRODUCTOS';
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
                'status' => $productoEntity->Status,
                'owner_id' => $productoEntity->IdOwner,
            ]);

            $productoEntity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_created'] ?? '',
                true,
                $productoEntity
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function GetAll(string $lang, int $perPage, int $page, int $ownerId, string $filter): RespuestaEntity
    {
        try {

            $query = DB::table($this->table)
                ->select('*')
                ->where('owner_id', $ownerId);

            // 👇 Aplicar filtro SOLO si viene con valor
            if (! empty(trim($filter))) {
                $query->where('nombre', 'LIKE', '%'.$filter.'%');
            }

            $query->orderBy('nombre');

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            $productos = collect($paginator->items())->map(function ($row) {
                $producto = new ProductoEntity;
                $producto->Id = $row->id;
                $producto->Nombre = $row->nombre;
                $producto->Descripcion = $row->descripcion;
                $producto->ValoracionGeneral = $row->valoracion_general;
                $producto->CreateAt = new DateTime($row->created_at);
                $producto->IdLenguaje = $row->id_lenguaje;
                $producto->IdTipoPago = $row->id_tipo_pago;
                $producto->IdTipoProducto = $row->id_tipo_producto;

                return $producto;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? '',
                true,
                [
                    'data' => $productos,
                    'pagination' => [
                        'total' => $paginator->total(),
                        'per_page' => $paginator->perPage(),
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                    ],
                ]
            );

        } catch (Exception $e) {

            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function GetById(int $id, string $lang): RespuestaEntity
    {
        try {

            $row = DB::table($this->table)
                ->where('id', $id)
                ->first();

            if (! $row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['product_not_found'] ?? '',
                    false,
                    null
                );
            }

            $producto = new ProductoEntity;
            $producto->Id = $row->id;
            $producto->IdTipoProducto = $row->id_tipo_producto;
            $producto->IdTipoPago = $row->id_tipo_pago;
            $producto->IdLenguaje = $row->id_lenguaje;
            $producto->Nombre = $row->nombre;
            $producto->Descripcion = $row->descripcion;
            $producto->ValoracionGeneral = $row->valoracion_general;
            $producto->CreateAt = new \DateTime($row->created_at);
            $producto->Status = $row->status;
            $producto->IdOwner = $row->owner_id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_found'] ?? '',
                true,
                $producto
            );

        } catch (Exception $e) {

            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function Update(ProductoEntity $productoEntity, string $lang): RespuestaEntity
    {
        return new RespuestaEntity(
            $this->translations[$lang]['error'] ?? '',
            false,
            null
        );
    }
}
