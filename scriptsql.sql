CREATE TABLE "TIPO_USUARIO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(20) NOT NULL
);

CREATE TABLE "USUARIO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombres varchar(100) NOT NULL,
    id_tipo_usuario integer NOT NULL,
    apellidos varchar(100) NOT NULL,
    correo varchar(100) NOT NULL,
    password varchar(255) NOT NULL,
    imagen varchar(255) NOT NULL,
    telefono varchar(20) NOT NULL,
    status boolean NOT NULL DEFAULT true,

    CONSTRAINT fk_tipo_usuario
        FOREIGN KEY (id_tipo_usuario)
        REFERENCES "TIPO_USUARIO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "TOKENS_AUTH" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    token varchar(255) NOT NULL,
    id_usuario integer NOT NULL,


    CONSTRAINT fk_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES "USUARIO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "TIPO_PRODUCTO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(50) NOT NULL
);

CREATE TABLE "TIPO_PAGO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(50) NOT NULL
);

CREATE TABLE "TIPO_SETUP" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(50) NOT NULL
);

CREATE TABLE "PRODUCTO_SETUP" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_tipo_setup integer NOT NULL,
    amount integer NOT NULL,

    CONSTRAINT fk_tipo_setup
        FOREIGN KEY (id_tipo_setup)
        REFERENCES "TIPO_SETUP"(id)
        ON DELETE CASCADE
);

CREATE TABLE "LENGUAJE" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(255) NOT NULL
);

CREATE TABLE "PAIS" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(255) NOT NULL
);

CREATE TABLE "PRODUCTOS" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_tipo_producto integer NOT NULL,
    id_tipo_pago integer NOT NULL,
    id_producto_setup integer NOT NULL,
    id_lenguaje integer NOT NULL,
    id_pais integer NOT NULL,
    nombre varchar(255) NOT NULL,
    descripcion text NOT NULL,
    valoracion_general integer NOT NULL,
    status boolean NOT NULL DEFAULT true,
    created_at timestamptz NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_pais
        FOREIGN KEY (id_pais)
        REFERENCES "PAIS"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tipo_producto
        FOREIGN KEY (id_tipo_producto)
        REFERENCES "TIPO_PRODUCTO"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tipo_pago
        FOREIGN KEY (id_tipo_pago)
        REFERENCES "TIPO_PAGO"(id)
        ON DELETE CASCADE,
        
    CONSTRAINT fk_producto_setup
        FOREIGN KEY (id_producto_setup)
        REFERENCES "PRODUCTO_SETUP"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_lenguaje
        FOREIGN KEY (id_lenguaje)
        REFERENCES "LENGUAJE"(id)
        ON DELETE CASCADE
);

CREATE TABLE "IMAGENES_PRODUCTO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_producto integer NOT NULL,
    ruta varchar(255) NOT NULL,

    CONSTRAINT fk_producto
        FOREIGN KEY (id_producto)
        REFERENCES "PRODUCTOS"(id)
        ON DELETE CASCADE
);

CREATE TABLE "CATEGORIAS" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(255) NOT NULL
);

CREATE TABLE "CATEGORIAS_PRODUCTO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_producto integer NOT NULL,
    id_categoria integer NOT NULL,


    CONSTRAINT fk_producto
        FOREIGN KEY (id_producto)
        REFERENCES "PRODUCTOS"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES "CATEGORIAS"(id)
        ON DELETE CASCADE
);

CREATE TABLE "VALORACIONES" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_usuario integer NOT NULL,
    id_producto integer NOT NULL,
    valoracion integer NOT NULL,
    comentario text NOT NULL,

    CONSTRAINT fk_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES "USUARIO"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_producto
        FOREIGN KEY (id_producto)
        REFERENCES "PRODUCTOS"(id)
        ON DELETE CASCADE
);

CREATE TABLE "SKU_PRODUCTO" (
    sku VARCHAR(20) PRIMARY KEY,
    id_producto integer NOT NULL,

    CONSTRAINT fk_producto
        FOREIGN KEY (id_producto)
        REFERENCES "PRODUCTOS"(id)
        ON DELETE CASCADE
);

CREATE TABLE "PRODUCTO_BASICO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sku_producto VARCHAR(20) NOT NULL,
    precio integer NOT NULL,
    descuento integer NOT NULL,

    CONSTRAINT fk_sku_producto
        FOREIGN KEY (sku_producto)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE
);

CREATE TABLE "PRODUCTO_VARIANTE" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sku_producto VARCHAR(20) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    precio integer NOT NULL,
    descuento integer NOT NULL,

    CONSTRAINT fk_sku_producto
        FOREIGN KEY (sku_producto)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE
);

CREATE TABLE "PRODUCTO_PLAN" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sku_producto VARCHAR(20) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    etiqueta VARCHAR(255) NOT NULL,
    precio integer NOT NULL,
    descuento integer NOT NULL,

    CONSTRAINT fk_sku_producto
        FOREIGN KEY (sku_producto)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE
);

CREATE TABLE "DETALLES_PLAN" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_producto_plan integer NOT NULL,
    detalle VARCHAR(255) NOT NULL,


    CONSTRAINT fk_producto_plan
        FOREIGN KEY (id_producto_plan)
        REFERENCES "PRODUCTO_PLAN"(id)
        ON DELETE CASCADE
);

CREATE TABLE "CARRITO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_usuario integer NOT NULL,

    CONSTRAINT fk_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES "USUARIO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "PRODUCTOS_CARRITO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sku_producto VARCHAR(20) NOT NULL,
    id_carrito integer NOT NULL,
    cantidad integer NOT NULL,

    CONSTRAINT fk_sku_producto
        FOREIGN KEY (sku_producto)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE,
    
    CONSTRAINT fk_carrito
        FOREIGN KEY (id_carrito)
        REFERENCES "CARRITO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "MONEDA" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL,
    equivalencia_dolar integer NOT NULL
);

CREATE TABLE "ORDENES" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_usuario integer NOT NULL,
    id_moneda integer NOT NULL,
    checkout_id VARCHAR(255) NOT NULL,
    total integer NOT NULL,
    metodo_pago VARCHAR(30) NOT NULL,
    pait_at timestamptz NOT NULL,
    estado_pago VARCHAR(30) NOT NULL,

    CONSTRAINT fk_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES "USUARIO"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_moneda
        FOREIGN KEY (id_moneda)
        REFERENCES "MONEDA"(id)
        ON DELETE CASCADE
);


CREATE TABLE "ITEMS_ORDEN" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_orden integer NOT NULL,
    id_tipo_pago integer NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    cantidad integer NOT NULL,
    precio_unitario integer NOT NULL,
    precio_total integer NOT NULL,
    estado VARCHAR(255) NOT NULL,

    CONSTRAINT fk_orden
        FOREIGN KEY (id_orden)
        REFERENCES "ORDENES"(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tipo_pago
        FOREIGN KEY (id_tipo_pago)
        REFERENCES "TIPO_PAGO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "SUSCRIPCIONES" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_item_orden integer NOT NULL,
    id_subcription_stripe VARCHAR(255) NOT NULL,
    last_payment_at timestamptz NOT NULL,
    estado_suscripcion VARCHAR(50) NOT NULL,

    CONSTRAINT fk_item_orden
        FOREIGN KEY (id_item_orden)
        REFERENCES "ITEMS_ORDEN"(id)
        ON DELETE CASCADE
);

CREATE TABLE "TIPO_CUPON" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE "CUPONES" (
    codigo_cupon VARCHAR(50) NOT NULL PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    expired_at timestamptz NOT NULL,
    estado_cupon boolean NOT NULL DEFAULT true,
    id_tipo_cupon integer NOT NULL,
    valor integer NOT NULL,
    max_usos integer DEFAULT NULL,
    usos_actuales integer NOT NULL DEFAULT 0,
    max_usos_por_usuario integer DEFAULT NULL,

    CONSTRAINT fk_tipo_cupon
        FOREIGN KEY (id_tipo_cupon)
        REFERENCES "TIPO_CUPON"(id)
        ON DELETE CASCADE
);

CREATE TABLE "CANJES_CUPONES" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    codigo_cupon VARCHAR(50) NOT NULL,
    id_usuario integer NOT NULL,

    CONSTRAINT fk_cupon
        FOREIGN KEY (codigo_cupon)
        REFERENCES "CUPONES"(codigo_cupon)
        ON DELETE CASCADE,

     CONSTRAINT fk_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES "USUARIO"(id)
        ON DELETE CASCADE
);

CREATE TABLE "CUPONES_PRODUCTO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    codigo_cupon VARCHAR(50) NOT NULL,
    sku_producto VARCHAR(20) NOT NULL,

    CONSTRAINT fk_cupon
        FOREIGN KEY (codigo_cupon)
        REFERENCES "CUPONES"(codigo_cupon)
        ON DELETE CASCADE,

     CONSTRAINT fk_sku_producto
        FOREIGN KEY (sku_producto)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE
);

CREATE TABLE "CODIGO_VERIFICACION" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    expired_at timestamptz NOT NULL,
    status boolean NOT NULL DEFAULT true
);

-- CREATE TABLE "TIPO_MENSAJE" (
--     id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
--     nombre VARCHAR(10) NOT NULL
-- );

-- CREATE TABLE "CHAT_BOT" (
--     id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
--     nombre VARCHAR(100) NOT NULL,
--     correo VARCHAR(100) NOT NULL,
--     status boolean NOT NULL DEFAULT true,
--     chat_asesor boolean NOT NULL,
--     created_at timestamptz NOT NULL
-- );

CREATE TABLE "PAIS_PRODUCTO" (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_pais integer NOT NULL,
    sku VARCHAR(20) NOT NULL,

     CONSTRAINT fk_pais
        FOREIGN KEY (id_pais)
        REFERENCES "PAIS"(id)
        ON DELETE CASCADE,

        CONSTRAINT fk_sku
        FOREIGN KEY (sku)
        REFERENCES "SKU_PRODUCTO"(sku)
        ON DELETE CASCADE
);