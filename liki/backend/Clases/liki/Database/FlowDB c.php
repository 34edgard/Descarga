<?php

namespace Liki\Database;

use Liki\Database\ConsultasBD;

use Exception;
class FlowDB{
      protected  $tabla ;
      public $Consultas_BD;
      public $consultaArray;
      public $sql = '';
      public $campos = '';
      public $valores = '';
      public $orderBy = '';
      public $where = '';
      public $limit = '';
        
        
        private static $instance = null;  
            private static $connection = null;  
              
            public static function conf(string $modelClass): self {  
                if (self::$instance === null) {  
                    self::$instance = new self();  
                    self::$connection = new ConsultasBD(); // Única conexión  
                }  
                  
                // Obtener nombre de tabla del modelo  
                $model = new $modelClass();  
                self::$instance->tabla = $model->getTableName();  
               // self::$instance->tabla = self::$instance->tabla;  
                  
                return self::$instance;  
            }  
        
        
    public function __construct(){
      
   $this->Consultas_BD = new ConsultasBD;
   }
    
    public function select(){
      $this->sql = 'SELECT '
     .$this->campos.' FROM '
     .$this->tabla
     .$this->orderBy
     .$this->where
     .$this->limit;
      
    }
    
    public function insertInto(array $datos){
      $this->sql = 'INSERT INTO '
      .$this->tabla.
      '('.$this->campos.
       ') VALUES ( '
      .$this->valores.')'
      .$this->where;
       
      $parametrosConsulta = [];
    }
    
    public function update(array $datos){
      $this->sql = "UPDATE "
      .$this->tabla
      .' SET '
      .$this->campos
      .$this->valores
      .$this->where;   
        
      
    
    }
    
    
    public function eliminar(array $datos){
      $datos['tabla'] = $this->tabla; 
      $parametrosEliminar = [];
    
 }


public function campos(array $campos){
   $ncampos = count($campos) -1;
   foreach($campos as $id => $campo){
       $this->campos .= " ".$campo;
       if($id < $ncampos) 
     $this->campos .= ",";
    }
     return $this;
}
public function valores(array $valores){
    
 $nvalores = count($valores) -1;
    foreach($valores as $id => $valore){
        if(is_string($valore)) 
         $valore = "'$valore'";
    $this->valores .= " ".$valore;
    if($id < $nvalores) 
  $this->valores .= ",";
 }

    return $this;
}

public function tabla(string $tabla = ''){
    $this->tabla = $tabla;
   return $this;
}

public function limit(int $limit, int $offset=0 ){
    
    if($limit == 0) return $this;
    $this->limit = " LIMIT ";
    if($offset > 0) 
     $this->limit .= $offset.",";
    $this->limit .= $limit;
       
    
       return $this;
}

public function orderBy(string $campo ,string $direccion ='DESC' ){
    $this->orderBy = ' ORDER BY ';
    $this->orderBy .= " ".$campo." ";
    $this->orderBy .= $direccion;
   return $this;
    
}

public function join($tipo,$campo,$where){
   // $this->consultar->addJoin($tipo, $campo,$where);
    return $this;
}


public function reset(){
     $this->tabla = '';
     $this->campos = '';
     $this->valores = '';
     $this->where = '';
     $this->orderBy = '';
     $this->limit = '';
     return $this;
}


public function where(array $where, array $operadorLogico =[]){
    
     
      $nOp = count($operadorLogico);
    if(count($where) == 0) return $this;
     $this->where = ' WHERE ';
      $i = 0;
      foreach($where as $name => $valor){
        if(is_array($valor) && count($valor) == 2){
            if(is_string($valor[1])) $valor[1] = "'$valor[1]'";
$this->where .= $name." ".$valor[0]." ".$valor[1];
        }else{
            if(is_string($valor)) $valor = "'$valor'";
$this->where .= $name." = ".$valor;
        }
        
         if($nOp >0 && $i < $nOp){
             $this->where .= ' '.$operadorLogico[$i].' ';
         }
        $i++;
      }
    return $this;
}





public function get(array $where = [],array $op=[]){
   $this->where($where,$op);
   $this->select();
   $parametrosConsulta =[];
   $resul = $this->Consultas_BD->consultarRegistro($this->sql,$parametrosConsulta);
   $this->reset();
   return $resul;
}

public function post(array $valores){
     $this->valores($valores);
     $this->insertInto();
     $parametrosRegistro =[];
     $this->Consultas_BD->ejecutarConsulta($this->sql,$parametrosRegistro);
      
     $this->reset();
}
public function put(array $where = [], array $op =[]){
      $this->where($where,$op); 
      $this->update();
      $parametrosRegistro =[];
      $this->Consultas_BD->ejecutarConsulta($this->sql,$parametrosRegistro);
       
      $this->reset();
}
public function delete(array $where = []){
      $this->where($where);
      $this->delete();
      $parametrosRegistro =[];
      $this->Consultas_BD->ejecutarConsulta($this->sql,$parametrosRegistro);
      $this->reset();
}
    
}
/*
$c =  new FlowDB();

$c->tabla('correo');
$c->campos(['c1','c2','c3','c4']);
$c->valores(['c1',3,'c3',4]);
$c->where([],[]);
$c->orderBy('vvv');
$c->limit(4,5);
$c->get();
echo $c->sql;
*/