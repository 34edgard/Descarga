<?php

$startMem = memory_get_usage();
$inicio = microtime(true); // Guarda el tiempo actual como un número flotante

include "./conf.php";
include "./backend/autoload.php";



use Liki\Routing\Ruta;
//use Liki\Plantillas\Flow;


Ruta::group('app/Docente');

Ruta::group('app/Plantel');



Ruta::group('liki/toolsDep');


/*


Ruta::group('app/Estudiante',true);

Ruta::group('app/Reprecentante',true);

Ruta::group('app/Direccion',true);
Ruta::group('app/Reportes',true);



Ruta::group('app/Admin',true);

*/
Ruta::group('app/Paginas');

Ruta::group('liki/builders');

Ruta::group('app/Sesiones');

Ruta::group('app/Usuario');

// Run the router 
Ruta::dispatch();
$fin = microtime(true); // Guarda el tiempo final
$tiempo_total = $fin - $inicio; // Calcula la diferencia

echo "El proceso tomó: " .round($tiempo_total,3) . " segundos.<br />";

echo "Memoria usada: " . round((memory_get_usage() - $startMem) / 1024 / 1024, 2) . " MB\n";