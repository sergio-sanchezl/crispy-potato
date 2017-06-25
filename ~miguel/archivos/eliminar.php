<?php
	include ($_SERVER ['DOCUMENT_ROOT'] . '/lib/db.php');

	/* Comienza la sesión, si es necesario */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	$id_archivo = $_GET ["id"];
	$prop_archivo = $_GET ["propiet"];

	$tupla = obtener_archivo ($id_archivo, $prop_archivo);

	if ($tupla)
	{
		$datos = $tupla ["datos"];
		$nombre = $tupla ["nombre"];
	}

	/* Comprueba que el usuario tenga acceso a ese archivo */
	if ( (hash_equals ($_SESSION ["usuario"], $prop_archivo))
	   || (preg_match ("/^[01]{5}1$/", $tupla ["permisos"]))
	)
	{
		/* Elimina el archivo */
		$GLOBAL ["contenido_principal"]
			= eliminar_archivo ($id_archivo, $prop_archivo)?
				"Archivo eliminado con éxito"
				: "Error al eliminar el archivo";
	}
	else
	{
		$GLOBAL ["contenido_principal"] = "Acceso no permitido";
	}

	/* Muestra los archivos que quedan */
	include 'ver_archivos.php';
?>
