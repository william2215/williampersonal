<?php
declare (strict_types = 1);
// Si no se ha enviado nada por el POST y se intenta acceder al archivo se retornará a la página de inicio
    require_once '../models/Personas.php';//Pega el codigo literal mente de modelo personas
    $persona = new Personas();//Se instancia la clase persona 
    $data  =array();//se define el dato igual que un vector
    $paginacion = array();
    $pagina=$_POST['pagina'] ?? 1;
    $termino = $_POST['termino'] ?? "";//Se agarra por POST lo que escriba el user
    $paginacion = $persona->getPagination();
    $filasTotal= $paginacion['filasTotal'];
    $filasPagina = $paginacion['filasPagina'];
    $empezarDesde=($pagina-1)* $filasPagina;

    if ($termino!= '') {//Si el termino no esta vacio
        $data = $persona->getSearch($termino);//Se agarra en un vector lo la variable termino y se manda por paramaetro a GETSEARCH()
    }else{//si no 
       $data = $persona->getAll($empezarDesde,$filasPagina);//Se agarra todo lo vacio 
   }
   echo $persona->showTable($data);//Se imprime la $data en la tabla
