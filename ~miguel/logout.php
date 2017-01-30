<?php
	/* Carga los datos de la sesión actual (si es necesario) y luego los borra */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	session_unset ();

	$GLOBAL ["contenido_principal"] = "Desconexión realizada con éxito";

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
