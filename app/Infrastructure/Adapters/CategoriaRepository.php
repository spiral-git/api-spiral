<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\CategoriaEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ICategoriaRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoriaRepository implements ICategoriaRepository
{
    public string $table;

    private array $translations = [
        "es" => [
            "category_created" => "Categoría creada correctamente",
            "category_updated" => "Categoría actualizada correctamente",
            "category_not_found_update" => "No se encontró la categoría para actualizar",
            "categories_retrieved" => "Categorías obtenidas correctamente",
            "category_found" => "Categoría encontrada",
            "category_not_found" => "Categoría no encontrada",
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "category_created" => "Category created successfully",
            "category_updated" => "Category updated successfully",
            "category_not_found_update" => "No category found to update",
            "categories_retrieved" => "Categories retrieved successfully",
            "category_found" => "Category found",
            "category_not_found" => "Category not found",
            "error" => "An error occurred"
        ]
    ];

    public function __construct()
    {
        $this->table = "CATEGORIAS";
    }

    public function Create(CategoriaEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'nombre' => $entity->Nombre,
                'status' => $entity->Status,
                'id_lenguaje' => $entity->IdLenguaje
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['category_created'] ?? "",
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

    public function Update(CategoriaEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'nombre' => $entity->Nombre,
                    'status' => $entity->Status,
                    'id_lenguaje' => $entity->IdLenguaje
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['category_not_found_update'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['category_updated'] ?? "",
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

    public function GetAll(int $idLenguaje, string $lang): RespuestaEntity
    {
        try {
            $rows = DB::table($this->table)
                ->where('id_lenguaje', $idLenguaje)
                ->get();

            $entities = $rows->map(function ($row) {
                $categoria = new CategoriaEntity();
                $categoria->Id = $row->id;
                $categoria->Nombre = $row->nombre;
                $categoria->Status = $row->status; 
                $categoria->IdLenguaje = $row->id_lenguaje;
                return $categoria;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['categories_retrieved'] ?? "",
                true,
                $entities
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
        try {
            $row = DB::table($this->table)
                ->where("nombre", $name)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['category_not_found'] ?? "",
                    false,
                    null
                );
            }

            $categoria = new CategoriaEntity();
            $categoria->Id = $row->id;
            $categoria->Nombre = $row->nombre;
            $categoria->Status = $row->status;
            $categoria->IdLenguaje = $row->id_lenguaje;

            return new RespuestaEntity(
                $this->translations[$lang]['category_found'] ?? "",
                true,
                $categoria
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