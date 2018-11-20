<?php
// Si no se ha enviado una petición mediante AJAX se retornará a página inicio
//if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../models/Personas.php';
	$persona = new Personas();
	$paginacion=array();
	$paginacion = $persona->getPagination();
	$filasTotal = $paginacion['filasTotal'];
	$filasPagina = $paginacion['filasPagina'];
	echo ceil($filasTotal /$filasPagina); 
//} else {
//	header('Location:../');
//}
