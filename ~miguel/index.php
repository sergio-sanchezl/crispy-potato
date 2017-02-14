<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";

	/* Si no se especifica un id válido, pone la página por defecto */
	$id = -7768;

	/* Obtiene el id especificado (si lo hay) */
	if (array_key_exists ("id", $_GET))
	{
		$id = $_GET["id"];
	}

	/* Lee el artículo de la base de datos */
	$GLOBAL ["contenido_principal"] = obtener_art ($id);

	/* Carga la plantilla */
	include $_SERVER['DOCUMENT_ROOT'] . "/plantillas/miguel.php";
?>
