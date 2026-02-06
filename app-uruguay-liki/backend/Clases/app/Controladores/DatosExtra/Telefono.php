<?php

namespace App\Controladores\DatosExtra;
use Liki\Modelo;
use Liki\DelegateFunction;


class Telefono extends Modelo{
  public function __construct(){
    parent::__construct('telefonos');
  }
  public static function NumeroTelefono($id){
     return DelegateFunction::exec('DatosPersonales/NumeroTelefono',$id);
  }

  public static function registrarTelefono($numero, $Propietario,$tipo,$id_Propietario){
       $p =[
           'numero'=>$numero,
           'Propietario'=>$Propietario,
           'tipo'=>$tipo,
           'id_Propietario'=>$id_Propietario
       ];
      DelegateFunction::exec('ManejadorTelefono/RegistrarTelefono',$p);
  }

}
