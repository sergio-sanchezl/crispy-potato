<?php
	include ($_SERVER ['DOCUMENT_ROOT'] . '/lib/db.php');

	/* Comienza la sesión, si es necesario */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	/* Crea un token para evitar CRSF, si es necesario */
	if (empty ($_SESSION ["CSRFToken"]))
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes(32));
	}
	$token = $_SESSION ["CSRFToken"];

	$id_archivo = $_GET ["id"];
	$prop_archivo = $_GET ["usuario"];

	$tupla = obtener_archivo ($id_archivo, $prop_archivo);

	if ($tupla)
	{
		$datos = $tupla ["datos"];
		$nombre = $tupla ["nombre"];
	}

	/* Comprueba que el usuario tenga acceso a ese archivo */
	if ( (hash_equals ($_SESSION ["usuario"], $prop_archivo))
	   || (preg_match ("/^[01]{4}1[01]$/", $tupla ["permisos"]))
	)
	{
		/* Prepara la descarga */
		header ('Content-Description: File Transfer');
		header ('Content-Type: application/octet-stream');
		header ('Content-Disposition: attachement; filename="'
			. (($nombre)? $nombre : "Desconocido.asdf")
			. '"');

		/* Convierte la cadena hexadecimal obtenida de la base de datos
		a bytes. Se hace doble 'pack' porque postgres hace otra conversión */
		$cadena = ltrim ($tupla ["datos"], "\\x");
		$datos = pack("H*", pack ("H*", $cadena));
		echo $datos;
	}
	else
	{
		$GLOBAL ["contenido_principal"] = "Acceso no permitido";

		/* Incluye la plantilla */
		include $_SERVER ['DOCUMENT_ROOT'] . '/plantillas/miguel.php';
	}
?>
