<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\ApiProductosVendidos;
use Controllers\ApiCajas;
use Model\ProductosVenta;
use Controllers\ApiFiados;
use Controllers\ApiInicio;
use Controllers\ApiVentas;
use Controllers\ApiEgresos;
use Controllers\ApiClientes;
use Controllers\ApiIngresos;
use Controllers\ApiReportes;
use Controllers\ApiUsuarios;
use Controllers\ApiProductos;
use Controllers\ApiCategorias;
use Controllers\ApiProveedores;
use Controllers\ApiMercadoLibre;
use Controllers\CajasController;
use Controllers\FiadosController;
use Controllers\VentasController;
use Controllers\EgresosController;
use Controllers\ClientesController;
use Controllers\IngresosController;
use Controllers\UsuariosController;
use Controllers\DashboardController;
use Controllers\ProductosController;
use Controllers\CategoriasController;
use Controllers\ProveedoresController;
use Controllers\MercadoLibreController;
use Controllers\TransaccionesController;
use Controllers\AvastesimientoController;
use Controllers\ProductosVentasController;

$router = new Router();


// Login
$router->get('/', [UsuariosController::class, 'redireccionLogin']);
$router->get('/login', [UsuariosController::class, 'login']);
$router->get('/logout', [UsuariosController::class, 'logout']);


//DasboardController

$router->get('/inicio',[DashboardController::class, 'index']);
$router->get('/usuarios', [UsuariosController::class, 'index']);
$router->get('/categorias', [CategoriasController::class, 'index']);
$router->get('/clientes', [ClientesController::class, 'index']);
$router->get('/productos', [ProductosController::class, 'index']);
$router->get('/proveedores', [ProveedoresController::class, 'index']);
$router->get('/cajas',[CajasController::class, 'index']);
$router->get('/fiados',[FiadosController::class, 'index']);
$router->get('/ingresos',[IngresosController::class, 'index']);
$router->get('/egresos',[EgresosController::class, 'index']);
$router->get('/compras',[AvastesimientoController::class, 'index']);
$router->get('/mercadolibre',[MercadoLibreController::class, 'index']);
$router->get('/productos-ventas',[ProductosVentasController::class, 'index']);

/* VENTAS CONTROLLERS */

$router->get('/crear-venta',[VentasController::class, 'crear']);
$router->get('/ventas',[VentasController::class, 'index']);
$router->get('/reporte-ventas',[VentasController::class, 'reporte']);






// $router->post('/usuario/comprobar-email', [UsuariosController::class, 'comprobarEmail']);




//API Usuarios
$router->get('/api/usuarios', [ApiUsuarios::class, 'usuarios']);
$router->get('/api/usuario', [ApiUsuarios::class, 'consultarUsuario']);
$router->post('/api/usuario/crear', [ApiUsuarios::class, 'crear']);
$router->post('/api/usuario/editar', [ApiUsuarios::class, 'editar']);
$router->post('/api/usuario/eliminar', [ApiUsuarios::class, 'eliminar']);
$router->post('/api/usuario/login', [ApiUsuarios::class, 'login']);
$router->get('/api/usuario/logout', [ApiUsuarios::class, 'logout']);

//Apicategorias

$router->get('/api/categorias', [ApiCategorias::class, 'categorias']);
$router->get('/api/categoria', [ApiCategorias::class, 'consultarCategoria']);
$router->post('/api/categoria/crear', [ApiCategorias::class, 'crear']);
$router->post('/api/categoria/editar', [ApiCategorias::class, 'editar']);
$router->post('/api/categoria/eliminar', [ApiCategorias::class, 'eliminar']);
$router->get('/api/productos-categorias', [ApiCategorias::class, 'categoriasAll']);


//API Clientes

$router->get('/api/clientes', [ApiClientes::class, 'clientes']);
$router->get('/api/cliente', [ApiClientes::class, 'consultarCliente']);
$router->post('/api/cliente/crear', [ApiClientes::class, 'crear']);
$router->post('/api/cliente/editar', [ApiClientes::class, 'editar']);
$router->post('/api/cliente/eliminar', [ApiClientes::class, 'eliminar']);

//API proveedores
$router->get('/api/proveedores', [ApiProveedores::class, 'proveedores']);
$router->get('/api/proveedor', [ApiProveedores::class, 'consultarProveedor']);
$router->post('/api/proveedor/crear', [ApiProveedores::class, 'crear']);
$router->post('/api/proveedor/editar', [ApiProveedores::class, 'editar']);
$router->post('/api/proveedor/eliminar', [ApiProveedores::class, 'eliminar']);
$router->get('/api/productos-proveedores', [ApiProveedores::class, 'proveedoresAll']);

//
$router->get('/api/productos-vendidos', [ApiProductosVendidos::class, 'productosVendidos']);

// API productos
$router->get('/api/productos', [ApiProductos::class, 'productos']);
$router->post('/api/producto/crear', [ApiProductos::class, 'crear']);
$router->post('/api/producto/editar', [ApiProductos::class, 'editar']);
$router->post('/api/producto/editar-stock', [ApiProductos::class, 'editarStock']);
$router->post('/api/producto/eliminar', [ApiProductos::class, 'eliminar']);
$router->get('/api/producto', [ApiProductos::class, 'consultarProducto']);
$router->get('/api/compras', [ApiProductos::class, 'avastecimiento']);





/* API DE LAS VENTAS */
$router->get('/api/ventas',[ApiVentas::class, 'ventas']);
$router->get('/api/venta',[ApiVentas::class, 'venta']);
$router->post('/api/crear-venta',[ApiVentas::class, 'crear']);
$router->post('/api/revisar-venta',[ApiVentas::class, 'revisarPagosAsociados']);
$router->post('/api/editar-venta',[ApiVentas::class, 'editar']);
$router->post('/api/venta/eliminar',[ApiVentas::class, 'eliminar']);
$router->get('/api/productos-ventas',[ApiVentas::class, 'productos']);
$router->get('/api/clientes-ventas',[ApiVentas::class, 'clientes']);
$router->get('/api/clientes-ventas-fiadas',[ApiVentas::class, 'clientesFiados']);
$router->get('/api/codigo-venta',[ApiVentas::class, 'codigoVenta']);

$router->get('/api/imprimir-venta',[ApiVentas::class, 'imprimirVenta']);




/* api a las ventas por mercado libre */
$router->get('/api/mercadolibre',[ApiMercadoLibre::class, 'index']);
$router->get('/api/pagos-auto',[ApiMercadoLibre::class, 'pagosAuto']);

/* API ereporte de ventas */
$router->post('/api/info-general',[ApiReportes::class, 'info']);


/* API DE LAS cajas*/

$router->get('/api/cajas',[ApiCajas::class, 'cajas']);
$router->get('/api/caja',[ApiCajas::class, 'caja']);
$router->post('/api/caja/crear',[ApiCajas::class, 'crear']);
$router->post('/api/caja/editar',[ApiCajas::class, 'editar']);
$router->post('/api/caja/eliminar',[ApiCajas::class, 'eliminar']);
$router->post('/api/caja/cerrar',[ApiCajas::class, 'cerrar']);

/* API de las cuotas o fiados */

$router->get('/api/pagos-cuotas',[ApiFiados::class, 'pagosCuotas']);
// $router->get('/api/cuotas',[ApiFiados::class, 'cuotas']);

$router->get('/api/productos-fiados',[ApiFiados::class, 'productosFiados']);
$router->post('/api/pagar',[ApiFiados::class, 'pagar']);
$router->post('/api/eliminar-pago',[ApiFiados::class, 'eliminarPago']);

/* api ingresos */
$router->get('/api/ingresos', [ApiIngresos::class, 'ingresos']);
$router->post('/api/ingreso/crear', [ApiIngresos::class, 'crear']);
$router->post('/api/ingreso/editar', [ApiIngresos::class, 'editar']);
$router->post('/api/ingreso/eliminar', [ApiIngresos::class, 'eliminar']);
$router->get('/api/ingreso', [ApiIngresos::class, 'ingreso']);

$router->get('/api/egresos', [ApiEgresos::class, 'egresos']);
$router->post('/api/egreso/crear', [ApiEgresos::class, 'crear']);
$router->post('/api/egreso/editar', [ApiEgresos::class, 'editar']);
$router->post('/api/egreso/eliminar', [ApiEgresos::class, 'eliminar']);
$router->get('/api/egreso', [ApiEgresos::class, 'egreso']);

//api Inicio
$router->get('/api/inicio', [ApiInicio::class, 'index']);

$router->comprobarRutas();


/* total_factura es el total de una venta

    total es el valor sobre el cual se calcula las ganancias por motivo de 
    ahi es donde se le quita la comision cuando es por mercado libre
*/