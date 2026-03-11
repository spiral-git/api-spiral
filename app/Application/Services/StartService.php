<?php


namespace App\Application\Services;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\RespuestaEntity;

// aca se crean: 
//  monedas default,
//  tipo de mensaje.

class StartService
{

    protected TipoUsuarioService $_usuarioTipoService;
    protected UsuarioService $_usuarioService;
    protected LenguajeService $_lenguajeService;
    protected PaisService $_paisService;
    protected TipoCuponService $_tipoCuponService;
    protected TipoPagoService $_tipoPagoService;
    protected TipoProductoService $_tipoProductoService;
    protected TipoSetupService $_tipoSetupService;
    protected ProductoSetupService $_productoSetupService;


    public function __construct(ProductoSetupService $productoSetupService, TipoUsuarioService $usuarioTipoService, UsuarioService $usuarioService, LenguajeService $lenguajeService, PaisService $paisService, TipoCuponService $tipoCuponService, TipoPagoService $tipoPagoService, TipoProductoService $tipoProductoService, TipoSetupService $tipoSetupService)
    {
        $this->_usuarioTipoService = $usuarioTipoService;
        $this->_usuarioService = $usuarioService;

        $this->_lenguajeService = $lenguajeService;
        $this->_paisService = $paisService;
        $this->_tipoCuponService = $tipoCuponService;

        $this->_tipoPagoService = $tipoPagoService;
        $this->_tipoProductoService = $tipoProductoService;
        $this->_tipoSetupService = $tipoSetupService;
        $this->_productoSetupService = $productoSetupService;
    }

    public function Start(): RespuestaEntity
    {
        $this->StartTiposUsuarios();
        $this->StartUsuario();
        $this->StartLenguaje();
        $this->StartPaises();
        $this->StartTipoPago();
        $this->StartTipoProducto();
        $this->StartTipoCupon();
        $this->StartTipoSetup();
        $this->StartProductoSetup();

        return new RespuestaEntity("Finalizado...", true, null);
    }

    public function StartTipoSetup()
    {
        $this->_tipoSetupService->Create("Fijo", "es");
        $this->_tipoSetupService->Create("Porcentaje", "es");
    }

    public function StartProductoSetup()
    {
        $setupProducto = new ProductoSetupEntity();
        $setupProducto->Amount = 0;
        $resp = $this->_tipoSetupService->GetByName("Fijo", "es");
        $setupProducto->IdTipoSetup = $resp->Data->Id;
        $this->_productoSetupService->Create($setupProducto, "es");
    }

    public function StartTipoPago()
    {
        $this->_tipoPagoService->Create("Unico", "es");
        $this->_tipoPagoService->Create("Suscripcion", "es");
    }

    public function StartTipoProducto()
    {
        $this->_tipoProductoService->Create("Cotizacion", "es");
        $this->_tipoProductoService->Create("Basico", "es");
        $this->_tipoProductoService->Create("Plan", "es");
        $this->_tipoProductoService->Create("Variante", "es");
    }


    public function StartLenguaje()
    {
        $this->_lenguajeService->Create("ES", "es");
        $this->_lenguajeService->Create("EN", "es");
    }

    public function StartTiposUsuarios()
    {
        $this->_usuarioTipoService->Create("Administrador", "es");
        $this->_usuarioTipoService->Create("Cliente", "es");
    }

    public function StartTipoCupon()
    {
        $this->_tipoCuponService->Create("Porcentaje", "es");
        $this->_tipoCuponService->Create("Fijo", "es");
    }

    public function StartUsuario()
    {
        $respExist = $this->_usuarioService->GetByMail("Admin@Admin.com", "es");
        if ($respExist->IsSuccess) {
            return;
        }
        $dto = new UsuarioInputDto();
        $dto->Correo = "Admin@Admin.com";
        $dto->Password = "Admin2025*";
        $dto->Nombres = "ADMIN";
        $dto->Apellidos = "ADMIN";
        $dto->Imagen = "";
        $dto->Telefono = "";
        $this->_usuarioService->Crear($dto, "ADMINISTRADOR", "es");
    }


    public function StartPaises()
    {
        $paises = [
            "AFGANISTAN",
            "ALBANIA",
            "ALEMANIA",
            "ANDORRA",
            "ANGOLA",
            "ANTIGUA Y BARBUDA",
            "ARABIA SAUDITA",
            "ARGELIA",
            "ARGENTINA",
            "ARMENIA",
            "AUSTRALIA",
            "AUSTRIA",
            "AZERBAIYAN",
            "BAHAMAS",
            "BANGLADES",
            "BARBADOS",
            "BARÉIN",
            "BELGICA",
            "BELICE",
            "BENIN",
            "BIELORRUSIA",
            "BIRMANIA",
            "BOLIVIA",
            "BOSNIA Y HERZEGOVINA",
            "BOTSUANA",
            "BRASIL",
            "BRUNEI",
            "BULGARIA",
            "BURKINA FASO",
            "BURUNDI",
            "BUTAN",
            "CABO VERDE",
            "CAMBOYA",
            "CAMERUN",
            "CANADA",
            "CATAR",
            "CHAD",
            "CHILE",
            "CHINA",
            "CHIPRE",
            "COLOMBIA",
            "COMORAS",
            "COREA DEL NORTE",
            "COREA DEL SUR",
            "COSTA DE MARFIL",
            "COSTA RICA",
            "CROACIA",
            "CUBA",
            "DINAMARCA",
            "DOMINICA",
            "ECUADOR",
            "EGIPTO",
            "EL SALVADOR",
            "EMIRATOS ARABES UNIDOS",
            "ERITREA",
            "ESLOVAQUIA",
            "ESLOVENIA",
            "ESPAÑA",
            "ESTADOS UNIDOS",
            "ESTONIA",
            "ESUATINI",
            "ETIOPIA",
            "FILIPINAS",
            "FINLANDIA",
            "FIYI",
            "FRANCIA",
            "GABON",
            "GAMBIA",
            "GEORGIA",
            "GHANA",
            "GRANADA",
            "GRECIA",
            "GUATEMALA",
            "GUINEA",
            "GUINEA-BISSAU",
            "GUINEA ECUATORIAL",
            "GUYANA",
            "HAITI",
            "HONDURAS",
            "HUNGRIA",
            "INDIA",
            "INDONESIA",
            "IRAK",
            "IRAN",
            "IRLANDA",
            "ISLANDIA",
            "ISLAS MARSHALL",
            "ISLAS SALOMON",
            "ISRAEL",
            "ITALIA",
            "JAMAICA",
            "JAPON",
            "JORDANIA",
            "KAZAJISTAN",
            "KENIA",
            "KIRGUISTAN",
            "KIRIBATI",
            "KUWAIT",
            "LAOS",
            "LESOTO",
            "LETONIA",
            "LIBANO",
            "LIBERIA",
            "LIBIA",
            "LIECHTENSTEIN",
            "LITUANIA",
            "LUXEMBURGO",
            "MADAGASCAR",
            "MALASIA",
            "MALAWI",
            "MALDIVAS",
            "MALI",
            "MALTA",
            "MARRUECOS",
            "MAURICIO",
            "MAURITANIA",
            "MEXICO",
            "MICRONESIA",
            "MOLDAVIA",
            "MONACO",
            "MONGOLIA",
            "MONTENEGRO",
            "MOZAMBIQUE",
            "NAMIBIA",
            "NAURU",
            "NEPAL",
            "NICARAGUA",
            "NIGER",
            "NIGERIA",
            "NORUEGA",
            "NUEVA ZELANDA",
            "OMAN",
            "PAISES BAJOS",
            "PAKISTAN",
            "PALAOS",
            "PANAMA",
            "PAPUA NUEVA GUINEA",
            "PARAGUAY",
            "PERU",
            "POLONIA",
            "PORTUGAL",
            "REINO UNIDO",
            "REPUBLICA CENTROAFRICANA",
            "REPUBLICA CHECA",
            "REPUBLICA DEMOCRATICA DEL CONGO",
            "REPUBLICA DEL CONGO",
            "REPUBLICA DOMINICANA",
            "RUANDA",
            "RUMANIA",
            "RUSIA",
            "SAMOA",
            "SAN CRISTOBAL Y NIEVES",
            "SAN MARINO",
            "SAN VICENTE Y LAS GRANADINAS",
            "SANTA LUCIA",
            "SANTO TOME Y PRINCIPE",
            "SENEGAL",
            "SERBIA",
            "SEYCHELLES",
            "SIERRA LEONA",
            "SINGAPUR",
            "SIRIA",
            "SOMALIA",
            "SRI LANKA",
            "SUDAFRICA",
            "SUDAN",
            "SUDAN DEL SUR",
            "SUECIA",
            "SUIZA",
            "SURINAM",
            "TAILANDIA",
            "TANZANIA",
            "TAYIKISTAN",
            "TIMOR ORIENTAL",
            "TOGO",
            "TONGA",
            "TRINIDAD Y TOBAGO",
            "TUNEZ",
            "TURKMENISTAN",
            "TURQUIA",
            "TUVALU",
            "UCRANIA",
            "UGANDA",
            "URUGUAY",
            "UZBEKISTAN",
            "VANUATU",
            "VATICANO",
            "VENEZUELA",
            "VIETNAM",
            "YEMEN",
            "YIBUTI",
            "ZAMBIA",
            "ZIMBABUE"
        ];

        foreach ($paises as $pais) {
            $this->_paisService->Create($pais, "es");
        }
    }
}
