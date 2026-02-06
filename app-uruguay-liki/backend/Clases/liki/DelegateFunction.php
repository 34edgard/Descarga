<?php
namespace Liki;

class DelegateFunction{
    public static function exec($name = '',$parametros=[],$funcionesExtra =[]){
       $urlFlie = CONTOLLER_PATH.'Manejadores/'.$name.'.php';
         if(!is_file($urlFlie))
                 throw new \InvalidArgumentException("Expection no existe el archivo $name.php en la ruta $urlFlie");
         
       $class = include $urlFlie;
      return  $class::run($parametros,$funcionesExtra);    
    } 
      
    public static function loadData($name = ''){
         $urlFlie = CONTOLLER_PATH.'/'.$name.'.php';
             if(!is_file($urlFlie))
                     throw new \InvalidArgumentException("Expection no existe el archivo $name.php en la ruta $urlFlie");
             
         
         return include $urlFlie;
    }
}