<?php
declare (strict_types = 1);//PHP estrictamente version 7
require_once 'Conexion.php';//Conexion en el require

class Personas extends Conexion//Clase de de personas y se le extiende la clase de conexion 
{
    public function __construct()//se definen las variables de cada cosa al inciar el modelo
    {
        parent::__construct();//Se usa la clase heredada de la conexion
    }
    public function insert(string $nombre, string $pais, string $edad)
    {
        error_reporting(0);
        try {
            $query  = "INSERT INTO informacion VALUES (null,:nombre,:pais,:edad) ;";
            $result = $this->db->prepare($query);
            $result->execute(array(':nombre' => $nombre, ':pais' => $pais, ':edad' => $edad));
            echo 'BIEN';
        } catch (PDOException $e) {
            echo 'ERROR';
        }
    }

    public function delete(int $id)
    {
        error_reporting(0);
        try {
            $query  = "DELETE FROM informacion WHERE id=:id;";
            $result = $this->db->prepare($query);
            $result->execute(array(':id' => $id));
            echo 'BIEN';
        } catch (PDOException $e) {
            echo 'ERROR';
        }
    }

public function edit(int $id,string $nombre, string $pais, string $edad)
{
  error_reporting(0);
  try {
   $query = "UPDATE informacion SET nombre=:nombre,pais=:pais,edad=:edad WHERE id=:id;";
   $result=$this->db->prepare($query);
   $result->execute(array(':id'=>$id,':nombre'=>$nombre,':pais'=>$pais,':edad'=>$edad));
   if ($result->rowCount()) {
    echo "BIEN";
}else{
    echo "IGUAL";
}

} catch (PDOException $e) {
   echo "ERROR";

}

}

    public function getAll(int $desde,int $filas):array//se agarra TODO en un VECTOR se agarra la consulta
    {
        $query="SELECT * FROM informacion ORDER BY nombre LIMIT {$desde},{$filas}";//Se hace la consulta
        return $this->ConsultaSimple($query);////Se retorna la variable 
    }

    public function getSearch(string $termino): array//Se declara la funcion donde se va realizar la consulta como tal este parametro es el que enviamos en el controller list
    {
        $where = "WHERE nombre LIKE :nombre || pais LIKE :pais ORDER BY nombre ASC";//Se declara la consulta
        $array = array(':nombre' => '%' . $termino . '%', ':pais' => '%' . $termino . '%');//Se pasan los parametros del termino por ajax
        return $this->ConsultaCompleja($where, $array);//Se retorna a la consulta los nuevos valores
    }

    public function getPagination():array   {
        $query="SELECT COUNT(*) FROM informacion;";
        return array(
            'filasTotal'=>intval($this->db->query($query)->fetch(PDO::FETCH_BOTH)[0]),
            'filasPagina'=>5,

        );
    }

    public function showTable(array $array): string//Show table funcion para mostrar la tabla que va ser de tipo STRING lo que se va a devolver
    {
        $html = '';//Se decalra un html en formade Variable
        if (count($array)) {//Si hay un eleento en el array se grafica la tabla 
            $html = '   <table class="table table-striped" id="table">
            <thead>
            <th class="d-none"></th>
            <th>NOMBRE</th>
            <th>PAÍS</th>
            <th>EDAD</th>
            <th>OPCIONES</th>
            </thead>

            <tbody>
            ';
            foreach ($array as $value) {//Se recorre todo el vector
                $html .= '  <tr>
                <td class="d-none">' . $value['id'] . '</td>
                <td>' . $value['nombre'] . '</td>
                <td>' . $value['pais'] . '</td>
                <td>' . $value['edad'] . '</td>
                <td class="text-center">
                <button title="Editar este usuario" class="editar btn btn-secondary" data-toggle="modal" data-target="#ventanaModal">
                <i class="fa fa-pencil-square-o"></i>
                </button>

                <button title="Eliminar este usuario" type="button" class="eliminar btn btn-danger" data-toggle="modal" data-target="#ventanaModal">
                <i class="fa fa-trash-o"></i>
                </button>
                </td>
                </tr>
                ';
            }
            $html .= '  </tbody>
            </table>';
        } else {
            $html = '<h4 class="text-center">No hay datos...</h4>';//Si no hay datos en la tabla se muestra no hay datos
        }
        return $html;
    }
}
