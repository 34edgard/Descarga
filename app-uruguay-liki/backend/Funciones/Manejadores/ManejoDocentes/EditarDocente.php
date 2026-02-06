<?php
use App\Controladores\Personas\Docente;
use Liki\Database\FlowDB;
return new class {

  public static function run($p,$f){
    
   extract($p);
    FlowDB::conf(Docente::class)
    ->campos(['cedula','nombres','apellidos','fecha_nacimiento','id_aula'])
   ->valores([$cedula,$nombre,$apellido,$fecha_nacimiento,$id_aula])
      ->put(['cedula'=>$cedula]);
   

    
    
    $f[0]();
  }
};
