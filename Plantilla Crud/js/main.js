$(function(){//Funcion de JQUERY anonima para cuando se inicie la tabla se defina lo siguiente
	let input_busqueda = $('#txt_busqueda');//Esto significa que el INPUT de busqueda va ser igual al ID del campo de busqueda
	listar('');//La variable listar va iniciar vacia por ende significa que no hay nada 
	tipoListado(input_busqueda);//se para por parametro la variable del tipo listado
	crearPaginacion();
	ejecutarAccion();
});
let alerta = (opcion, respuesta) => {
    let mensaje = '';
    switch (opcion) {
        case 'insertar':
        mensaje = 'Usuario insertado correctamente.';
        break;
        case 'editar':
        mensaje = 'Información de usuario modificada con exito.';
        break;
        case 'eliminar':
        mensaje = 'Usuario eliminado exitosamente.';
        break;
    }
    switch (respuesta) {
        case 'BIEN':
        $('#alerta').html('<div class="alert alert-success text-center"><strong>¡BIEN! </strong>' + mensaje + '</div>');
        break;
        case 'ERROR':
        $('#alerta').html('<div class="alert alert-danger text-center"><strong>¡ERROR! </strong>Solicitud no procesada.</div>');
        break;
        case 'IGUAL':
        $('#alerta').html('<div class="alert alert-info text-center"><strong>¡ADVERTENCIA! </strong>Ha enviado los mismos datos.</div>');
        break;
        case 'VACIO':
        $('#alerta').html('<div class="alert alert-danger text-center"><strong>¡ERROR! </strong>No puede enviar datos vacíos.</div>');
        break;
    }
}
let ejecutarAccion = () => {
    $('#btn_guardar_cambios').on('click', function() {
        let opcion = $('#opcion').val();
        let id = $('#id').val();
        let nombre = $('#txt_nombre').val();
        let pais = $('#txt_pais').val();
        let edad = $('#txt_edad').val();
        $.ajax({
            url: 'controllers/controllerActions.php',
            method: 'POST',
            data: {
                opcion: opcion,
                id: id,
                nombre: nombre,
                pais: pais,
                edad: edad
            },
        }).done(function(data) {
  		//console.log(data);
  
  		            $('#gif').toggleClass('d-none');
            alerta(opcion, data);
            listar('');
            crearPaginacion();
            if (opcion == 'eliminar' && data == 'BIEN') {
                $('#btn_guardar_cambios').attr('disabled', true);
            }
            if (opcion == 'insertar' && data == 'BIEN') {
                $('#id').val('');
                $('#txt_nombre').val('');
                $('#txt_pais').val('');
                $('#txt_edad').val('');
            }
        });
  	});
    }

    


let prepararDatos = () => {
	let values = [];
	//Evento editar
	$('#table .editar').on('click',function(){
		values = ciclo($(this));
		$('#opcion').val('editar');
		$('#id').val(values[0]);
		$('#txt_nombre').val(values[1]);
		$('#txt_pais').val(values[2]);
		$('#txt_edad').val(values[3]);
		cambiarTitulo('editar usuarios');

	});

	$('#table .eliminar').on('click',function(){
		values = ciclo($(this));
		$('#opcion').val('eliminar');
		$('#id').val(values[0]);
		$('#txt_nombre').val(values[1]);
		$('#txt_pais').val(values[2]);
		$('#txt_edad').val(values[3]);
		cambiarTitulo('eliminar usuarios');

	});

	$('#btn_insertar').on('click', function() {
		$('#opcion').val('insertar');
		$('#id').val('');
		$('#txt_nombre').val('');
		$('#txt_pais').val('');
		$('#txt_edad').val('');
		cambiarTitulo('insertar usuarios');

	});


}
let ciclo = (selector) => {
	let datos = [];
	$(selector).parents('tr').find('td').each(function(i){
		if (i < 4) {
			datos[i] = $(this).text();
		}else{
			return false;
		}
	});
	return datos;
}
let cambiarTitulo = (titulo) => {
	$('.modal-header .modal-title').text(titulo);
}

let cambiarPagina=()=>{
	$('.page-item>.page-link').on('click', function() {
		$.ajax({
			url: 'controllers/controllerList.php',
			method: 'POST',
			data: {
				pagina: $(this).text()
			},
		}).done(function(data) {
			$('#div_tabla').html(data);
			prepararDatos();
		});
	});
}

let crearPaginacion = () => {
	$.ajax({
		url: 'controllers/controllerPagination.php',
		method: 'POST'
	}).done(function(data) {
		$('#pagination li').remove();
		for (var i = 1; i <= data; i++) {
			$('#pagination').append('<li class="page-item"><a class="page-link text-muted" href="#">' + i + '</a></li>');
		}
		cambiarPagina();
	});
}
let listar=(param)=>{//Se para por parametro lo que este adentro de la funcion listar
	$.ajax({
		url:'controllers/controllerList.php',
		method:'POST',
		data:{
			termino:param//Termino seria el objeto que tenga adentro al parametro pasado es un objeto con de javascript con un data que seria lis parametros
		}
	}).done(function(data) {//Cuando se haga 
		$('#div_tabla').html(data);//Se invoca la tabla y los datos 
		prepararDatos();

	});

}
let tipoListado = (input) => {//Se pasa el valor del input 
	$(input).on('keyup',function(){//cuando se velante la primera tecla al buscar se ejectuta la funcion
		let termino = '';//Se define termino en vacio
		if($(this).val()!=''){//si el termino tiene algo
			termino = $(this).val();//variable termino va a ser igual a lo que escriba la persona
		}
		listar(termino);//Se pasa por parametro hacia la funcion tipo listado el parametro de lo que escriba el usuario
	});
}