<?php
	/* Carga los datos de la sesión actual y luego los borra */
	session_start ();
	session_unset ();

	$GLOBAL ["contenido_principal"] = "Desconexión realizada con éxito";

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
