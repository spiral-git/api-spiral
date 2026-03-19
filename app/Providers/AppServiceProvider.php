<?php

namespace App\Providers;

use App\Application\Services\CategoriaService;
use App\Application\Services\ImagenProductoService;
use App\Application\Services\LenguajeService;
use App\Application\Services\PaisProductoService;
use App\Application\Services\PaisService;
use App\Application\Services\ProductoBasicoService;
use App\Application\Services\ProductoCategoriaService;
use App\Application\Services\ProductoCotizableService;
use App\Application\Services\ProductoPlanService;
use App\Application\Services\ProductoService;
use App\Application\Services\ProductoVarianteService;
use App\Application\Services\SkuService;
use App\Application\Services\StartService;
use App\Application\Services\TipoCuponService;
use App\Application\Services\TipoDescuentoService;
use App\Application\Services\TipoPagoService;
use App\Application\Services\TipoProductoService;
use App\Application\Services\TipoSetupService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\TokenAuthService;
use App\Application\Services\UsuarioService;
use App\Domain\Ports\ICategoriaProductoRepository;
use App\Domain\Ports\ICategoriaRepository;
use App\Domain\Ports\IDetallePlanRepository;
use App\Domain\Ports\IImagenRepository;
use App\Domain\Ports\ILenguajeRepository;
use App\Domain\Ports\IPaisProductoRepository;
use App\Domain\Ports\IPaisRepository;
use App\Domain\Ports\IProductoBasicoRepository;
use App\Domain\Ports\IProductoPlanRepository;
use App\Domain\Ports\IProductoRepository;
use App\Domain\Ports\IProductoVarianteRepository;
use App\Domain\Ports\ISkuRepository;
use App\Domain\Ports\ITipoCuponRepository;
use App\Domain\Ports\ITipoDescuentoRepository;
use App\Domain\Ports\ITipoPagoRepository;
use App\Domain\Ports\ITipoProductoRepository;
use App\Domain\Ports\ITipoSetupRepository;
use App\Domain\Ports\ITipoUsuarioRepository;
use App\Domain\Ports\ITokenAuthRepository;
use App\Domain\Ports\IUsuarioRepository;
use App\Infrastructure\Adapters\CategoriaProductoRepository;
use App\Infrastructure\Adapters\CategoriaRepository;
use App\Infrastructure\Adapters\DetallePlanRepository;
use App\Infrastructure\Adapters\ImagenRepository;
use App\Infrastructure\Adapters\LenguajeRepository;
use App\Infrastructure\Adapters\PaisProductoRepository;
use App\Infrastructure\Adapters\PaisRepository;
use App\Infrastructure\Adapters\ProductoBasicoRepository;
use App\Infrastructure\Adapters\ProductoPlanRepository;
use App\Infrastructure\Adapters\ProductoRepository;
use App\Infrastructure\Adapters\ProductoVarianteRepository;
use App\Infrastructure\Adapters\SkuRepository;
use App\Infrastructure\Adapters\TipoCuponRepository;
use App\Infrastructure\Adapters\TipoDescuentoRepository;
use App\Infrastructure\Adapters\TipoPagoRepository;
use App\Infrastructure\Adapters\TipoProductoRepository;
use App\Infrastructure\Adapters\TipoSetupRepository;
use App\Infrastructure\Adapters\TipoUsuarioRepository;
use App\Infrastructure\Adapters\TokenAuthRepository;
use App\Infrastructure\Adapters\UsuarioRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            ITipoUsuarioRepository::class,
            TipoUsuarioRepository::class
        );

        $this->app->singleton(
            TipoUsuarioService::class,
            TipoUsuarioService::class
        );

        $this->app->singleton(
            ITokenAuthRepository::class,
            TokenAuthRepository::class
        );

        $this->app->singleton(
            TokenAuthService::class,
            TokenAuthService::class
        );

        $this->app->singleton(
            IUsuarioRepository::class,
            UsuarioRepository::class
        );

        $this->app->singleton(
            UsuarioService::class,
            UsuarioService::class
        );

        $this->app->singleton(
            ITipoCuponRepository::class,
            TipoCuponRepository::class
        );

        $this->app->singleton(
            ITipoProductoRepository::class,
            TipoProductoRepository::class
        );

        $this->app->singleton(
            ITipoPagoRepository::class,
            TipoPagoRepository::class
        );

        $this->app->singleton(
            ILenguajeRepository::class,
            LenguajeRepository::class
        );

        $this->app->singleton(
            IPaisRepository::class,
            PaisRepository::class
        );

        $this->app->singleton(
            ITipoSetupRepository::class,
            TipoSetupRepository::class
        );


        $this->app->singleton(
            LenguajeService::class,
            LenguajeService::class
        );

        $this->app->singleton(
            PaisService::class,
            PaisService::class
        );

        $this->app->singleton(
            TipoPagoService::class,
            TipoPagoService::class
        );

        $this->app->singleton(
            TipoSetupService::class,
            TipoSetupService::class
        );

        $this->app->singleton(
            TipoCuponService::class,
            TipoCuponService::class
        );
        $this->app->singleton(
            TipoProductoService::class,
            TipoProductoService::class
        );

        $this->app->singleton(
            ISkuRepository::class,
            SkuRepository::class
        );

        $this->app->singleton(
            SkuService::class,
            SkuService::class
        );

        $this->app->singleton(
            IPaisProductoRepository::class,
            PaisProductoRepository::class
        );

        $this->app->singleton(
            PaisProductoService::class,
            PaisProductoService::class
        );

        $this->app->singleton(
            ICategoriaProductoRepository::class,
            CategoriaProductoRepository::class
        );

        $this->app->singleton(
            ProductoCategoriaService::class,
            ProductoCategoriaService::class
        );

        $this->app->singleton(
            ICategoriaRepository::class,
            CategoriaRepository::class
        );

        $this->app->singleton(
            CategoriaService::class,
            CategoriaService::class
        );

        $this->app->singleton(
            IImagenRepository::class,
            ImagenRepository::class
        );

        $this->app->singleton(
            ImagenProductoService::class,
            ImagenProductoService::class
        );

        $this->app->singleton(
            IDetallePlanRepository::class,
            DetallePlanRepository::class
        );


        $this->app->singleton(
            IProductoRepository::class,
            ProductoRepository::class
        );

        $this->app->singleton(
            ProductoService::class,
            ProductoService::class
        );

        $this->app->singleton(
            ProductoCotizableService::class,
            ProductoCotizableService::class
        );

        $this->app->singleton(
            ITipoDescuentoRepository::class,
            TipoDescuentoRepository::class
        );

        $this->app->singleton(
            TipoDescuentoService::class,
            TipoDescuentoService::class
        );

        $this->app->singleton(
            IProductoBasicoRepository::class,
            ProductoBasicoRepository::class
        );

        $this->app->singleton(
            ProductoBasicoService::class,
            ProductoBasicoService::class
        );

        $this->app->singleton(
            IProductoVarianteRepository::class,
            ProductoVarianteRepository::class
        );

        $this->app->singleton(
            ProductoVarianteService::class,
            ProductoVarianteService::class
        );

        $this->app->singleton(
            IProductoPlanRepository::class,
            ProductoPlanRepository::class
        );

        $this->app->singleton(
            ProductoPlanService::class,
            ProductoPlanService::class
        );

        //va al final de todo
        $this->app->singleton(
            StartService::class,
            StartService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
