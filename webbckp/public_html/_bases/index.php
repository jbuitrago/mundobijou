<?php

class index {
	
	public  function consulta_general ($valores=NULL){
	
		return "
			SELECT 	adm_bdconsulta.id_adm_bdconsulta AS id
			,		adm_bdconsulta.orden
			,		adm_bdconsulta.texto
			FROM 	adm_bdconsulta
			WHERE	adm_bdconsulta.orden > ".$valores[0]."
			;
		";
	
	}	

}