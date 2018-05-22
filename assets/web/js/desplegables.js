$(document).ready(function () {
    iniciar();
});

//iniciar
function iniciar(){

	$("#id_adm_auto_modelo").change(function () {
		auto_version($("#id_adm_auto_modelo").val());
    });
	
	$("#provincia").change(function () {
		consulta_ciudades($("#provincia").val());
    });

}

//Carga las opciones en el select auto version
function auto_version(id_adm_auto_modelo){
	desplegable_accion('id_adm_auto_version','esperar');
	//desplegable_accion('id_adm_auto_anio','cargando');
    $.ajax({
        type: "GET",
        url: "xml.php?lista=obtener_xml_auto_modelo&id="+id_adm_auto_modelo,
        datatype: "xml",
        success: function(xml){
            var contenido = '';
            var value = '';
            var texto = '';
            contenido += '<option value="" selected="selected">SELECCIONAR</option>';
            $(xml).find("datos").each(function(){
                value = $(this).find("id").text();
                texto = $(this).find("nombre").text();
                contenido += '<option value="'+value+'">'+texto+'</option>';
            });
			$("#id_adm_auto_version").html(contenido);
			$("#id_adm_auto_version").change(function(){
            //    auto_anio($("#id_adm_auto_version").val());
				auto_reparaciones($("#id_adm_auto_version").val());
            });
        }
    });
}

//Carga las opciones en el select auto anio
/*
function auto_anio(id_adm_auto_version){
	desplegable_accion('id_adm_auto_anio','esperar');
    $.ajax({
        type: "GET",
        url: "xml.php?lista=obtener_xml_auto_anio&id="+id_adm_auto_version,
        datatype: "xml",
        success: function(xml){
            var contenido = '';
            var value = '';
            var texto = '';
            contenido += '<option value="" selected="selected">SELECCIONAR</option>';
            $(xml).find("datos").each(function(){
                value = $(this).find("id").text();
                texto = $(this).find("nombre").text();
                contenido += '<option value="'+value+'">'+texto+'</option>';
            });
			$("#id_adm_auto_anio").html(contenido);
        }
    });
}
*/

//Carga las opciones en el select auto reparaciones
function auto_reparaciones(id_adm_auto_version){
	desplegable_accion('reparaciones','esperar');
	$('#reparaciones').load('reparaciones.php?id='+id_adm_auto_version);
}

//Carga las opciones en el select ciudades
function consulta_ciudades(id_provincia){
	desplegable_accion('ciudad','esperar');
	desplegable_accion('id_adm_concesionaria','cargando');
    $.ajax({
        type: "GET",
        url: "xml.php?lista=obtener_xml_provincia&id="+id_provincia,
        datatype: "xml",
        success: function(xml){
            var contenido = '';
            var value = '';
            var texto = '';
            contenido += '<option value="" selected="selected">SELECCIONAR</option>';
            $(xml).find("datos").each(function(){
                value = $(this).find("id").text();
                texto = $(this).find("nombre").text();
                contenido += '<option value="'+value+'">'+texto+'</option>';
            });
            $("#ciudad").html(contenido);
            $("#ciudad").change(function(){
                consulta_id_adm_concesionarias($("#ciudad").val());
            });
        }
    });
}

//Carga las opciones en el select id_adm_concesionarias
function consulta_id_adm_concesionarias(id_ciudad){
    desplegable_accion('id_adm_concesionaria','esperar');
	$.ajax({
        type: "GET",
        url: "xml.php?lista=obtener_xml_ciudad&id="+id_ciudad,
        datatype: "xml",
        success: function(xml){
            var contenido = '';
            var value = '';
            var texto = '';
            contenido += '<option value="">SELECCIONAR</option>';
            $(xml).find("datos").each(function(){
                value = $(this).find("id").text();
                texto = $(this).find("nombre").text();
                contenido += '<option value="'+value+'">'+texto+'</option>';
            });
            $("#id_adm_concesionaria").html(contenido);
        }
    });
}



//mostrar Espere... en un select
function desplegable_accion(desplegable,accion){
	if( accion == 'esperar' ){
		var accion_ver = "Espere...";
	}else if(  accion == 'cargando' ){
		var accion_ver = "CARGANDO";
	}
		
    var contenido = '<option value="" selected="selected">'+accion_ver+'</option>';
    $("#"+desplegable).html(contenido);
}

