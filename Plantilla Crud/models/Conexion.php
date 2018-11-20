<?php
declare (strict_types = 1);//Significa el modo estricto de php 7
class Conexion//se define la clase 
{
  protected $db;//se declara protegida
  public function __construct()//Se hace el constructor para guardar todas las variables
  {
    $this->db = $this->conectar();//Se hace el METODO
  }
  private function conectar()// se define la funcion conectar de manera privada
  {
    try{//TRY = PROBAR
      $HOST = '127.0.0.1';//LOCALHOST
      $DBNAME = 'personas';//NOMBRE DE BASE DE DATOS
      $USER = 'root';//Usuario
      $PASS = "";
      $con = new PDO("mysql:host={$HOST}; dbname={$DBNAME}",$USER,$PASS);//PDO conexion mas exacta
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//EXEPCION de la PDO
      $con->exec('SET CHARACTER SET UTF8');//definir el modo de tildes
    } catch (PDOException $e) {//Se capta la exepcion de la PDO
      echo $e->getMessage();//Se muestra el mensaje como tal
    }
    return $con;//Se retornar algo a la variable coneccion
  }
  protected function ConsultaSimple(string $query):array//Esto es para hacer consultas de datos
  {
     return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);//Agarra el $query que es el resultado del select de abajo
  }

  protected function ConsultaCompleja(string $where, array $array):array
  {
    $query="SELECT * FROM informacion {$where}";//este where lo que hace es agarrar el texto literal de consulta compleja y lo pone donde esta esta syntaxis {where}
    $result = $this->db->prepare($query);
    $result->execute($array);
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }
}
//$ins = new Conexion();//Instanciamiento de la clase
//print_r($ins->ConsultaSimple("SELECT * FROM informacion"));//Imprimir la consulta 
