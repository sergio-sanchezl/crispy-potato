<?php
	include '../lib/db.php';

	/* Comienza la sesión, si es necesario */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	/* Crea un token para evitar CRSF, si es necesario */
	if (empty($_SESSION ["CSRFToken"]))
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes(32));
	}
	$token = $_SESSION ["CSRFToken"];

	$formulario = "
	<form id=\"subir_arch\" action=\"subir_archivos.php\" method=\"POST\" enctype=\"multipart/form-data\">
		<fieldset>
				<legend>Subir archivo:</legend>
				<input type=\"hidden\" name=\"CSRFToken\" value=\"$token\">

				<p>
					<input style=\"border:none\" type=\"file\" name=\"archivo\">
				</p>
				<p>
					<label for=\"nombre\">Nombre del archivo:</label>
					<input type\"text\" name=\"nombre\">
				</p>
				<p>
					<label for=\"descr\">Descripción del archivo:</label>
					<input type\"text\" name=\"descr\">
				
				<p>
					Permisos para el archivo:
					<table style=\"border-spacing: 10px\">
					<tr>
						<td>Usuarios del mismo grupo</td>
						<td>Resto de usuarios</td>
					</tr>
					<tr>
						<td>
							<select name=\"gid\">
								<option value=\"00\">Ni lectura ni escritura</option>
								<option value=\"11\">Lectura y escritura</option>
								<option value=\"10\">Sólo lectura</option>
							<option value=\"01\">Sólo escritura</option>
							</select>
						</td>
							<td>
						<select name=\"resto\">
								<option value=\"00\">Ni lectura ni escritura</option>
								<option value=\"11\">Lectura y escritura</option>
								<option value=\"10\">Sólo lectura</option>
								<option value=\"01\">Sólo escritura</option>
							</select>
						</td>
					</tr>
					</table>
				</p>

				<input type=\"submit\" value=\"Subir\" name=\"submit\">
		</fieldset>
	</form>";

	/**
	 * Obtiene los permisos del archivo a partir de $gid y $resto.
	 */
	function ver_permisos ($gid, $resto)
	{
		$regexp = '/^[01][01]$/';
		$permisos = "11";

		if (!preg_match ($regexp, $gid))
		{
			$permisos .= "00";
		}
		else
		{
			$permisos .= $gid;
		}

		if (!preg_match ($regexp, $resto))
		{
			$permisos .= "00";
		}
		else
		{
			$permisos .= $resto;
		}

		return $permisos;
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
				$GLOBAL ["contenido_principal"] = "Subida de archivos no autorizada.";
			}
			else
			{
				$datos = file_get_contents($_FILES ["archivo"]["tmp_name"]);
				$usuario = $_SESSION ["usuario"];
				$descr = $_POST ["descr"];
				$nombre = empty ($_POST ["nombre"])? null : $_POST ["nombre"];
				$permisos = ver_permisos ($_POST ["gid"], $_POST ["resto"]);

				$GLOBAL ["contenido_principal"] = (insertar_archivo ($usuario, $datos, $descr, $nombre, $permisos))?
						"Archivo subido con éxito"
						: "Error al subir el archivo";
			}
		}
		else
		{
			$GLOBAL ["contenido_principal"] = $formulario;
		}
	}

	include '../plantillas/miguel.php';
?>
