<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ISetupProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoSetupRepository implements ISetupProductoRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "product_setup_created" => "Setup de producto creado correctamente",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "product_setup_created" => "Product setup created successfully",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PRODUCTO_SETUP";
    }

    public function Create(ProductoSetupEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'id_tipo_setup' => $entity->IdTipoSetup,
                'amount' => $entity->Amount
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_setup_created'] ?? "",
                true,
                $entity
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}