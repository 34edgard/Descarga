<?php
namespace App\Controladores\DatosExtra;
use Liki\Modelo;
use Liki\DelegateFunction;



class Tallas extends Modelo{
  public function __construct(){
    parent::__construct('tallas');
  }
}