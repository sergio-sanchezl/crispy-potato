<?php
	include '../lib/db.php';

	$mensaje = "Error al subir el archivo.";

	/* Comienza la sesión, si es necesario */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	/* Si no se ha iniciado sesión no se permite el acceso */
	if (empty ($_SESSION ["usuario"]))
	{
		$GLOBAL ["contenido_principal"] = "Para acceder a esta página hay que registrarse.
							<br/><a href=\"login.php\">Pulse aquí</a> para acceder.";
	}
	else
	{
		/* Si se recibe el parámetro "submit" en POST, se deduce que se quiere subir un archivo */
		if (!empty ($_POST ["submit"]))
		{
			/* Comprueba el token */
			if (!hash_equals ($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				$mensaje = "Subida de archivos no autorizada.";
			}
			else
			{
				$datos = file_get_contents($_FILES ["archivo"]["tmp_name"]);
				$usuario = $_SESSION ["usuario"];
				$descr = $_POST ["descr"];
				$nombre = empty ($_POST ["nombre"])? null : $_POST ["nombre"];
				$permisos = ver_permisos ($_POST ["gid"], $_POST ["resto"]);

				$mensaje = (insertar_archivo ($usuario, $datos, $descr, $nombre, $permisos))?
						"Archivo subido con éxito"
						: "Error al subir el archivo";
			}
		}
	}

	echo $mensaje;
?>
