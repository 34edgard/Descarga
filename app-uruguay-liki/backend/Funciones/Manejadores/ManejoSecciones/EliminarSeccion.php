<?php
use App\Controladores\Plantel\Secciones;
use App\Controladores\Plantel\Aulas;
use App\Controladores\Personas\DocenteSeccion;
use Liki\Database\FlowDB;

return new class {
    public static function run($p,$f){
        extract($p);
        //print_r($p);
        $datos = ['id_seccion'=>$eliminarSeccion];
       //print_r($datos);
        FlowDB::conf(Aulas::class)->delete($datos);
        FlowDB::conf(DocenteSeccion::class)->delete($datos);
       FlowDB::conf(Secciones::class)->delete($datos);
        $f[0]();
    }
};