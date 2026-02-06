<?php

namespace App\Controladores\Personas;
use Liki\Modelo;
use Liki\DelegateFunction;

class Estudiante extends Modelo{
  public function __construct(){
    parent::__construct('estudiante');
  }
  public static function ConsultarListaEstudiantes(){
      DelegateFunction::exec('ManejoEstudiantes/ConsultarListaEstudiantes');
  }
  public static function confirmarEliminacionEstudiante($p){
      DelegateFunction::exec('ManejoEstudiantes/ConfirmarEliminacionEstudiante',$p);
  }
  public static function registrarEstudiante($p,$f){
      DelegateFunction::exec('ManejoEstudiantes/RegistrarEstudiante',$p,$f);
  }
  public static function registrarDatosExtraEstudiante($p){
      DelegateFunction::exec('ManejoEstudiantes/RegistrarDatosExtraEstudiante',$p);
  }
  public static function FormularioEdicionEstudiante($p){
      DelegateFunction::exec('ManejoEstudiantes/FormularioEdicionEstudiante',$p);
  }
  public static function EliminarEstudiante($p,$f){
      DelegateFunction::exec('ManejoEstudiantes/EliminarEstudiante',$p,$f);
  }
  public static function ConsultarMatriculaEscolar($p,$f){
      DelegateFunction::exec('ManejoEstudiantes/ConsultarMatriculaEscolar',$p,$f);
  }
}

