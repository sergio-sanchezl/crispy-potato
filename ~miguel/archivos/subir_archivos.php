<?php
	include ($_SERVER ['DOCUMENT_ROOT'] . '/lib/db.php');

	/* Comienza la sesión, si es necesario */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	/* Crea un token para evitar CRSF */
	if (!isset ($_SESSION ["CSRFToken"]) )
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes (32));
	}
	$token = $_SESSION ["CSRFToken"];

	$formulario = "
	<form id=\"subir_arch\" action=\"/~miguel/archivos/subir_archivos.php\" method=\"POST\" enctype=\"multipart/form-data\">
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
		$GLOBALS ["contenido_principal"]
			= "Para acceder a esta página hay que registrarse. "
			. "<br/><a href=\"../cuentas/login.php\">Pulse aquí</a>"
			. " para acceder.";
	}
	else
	{
		/* Si se recibe el parámetro "submit" en POST, se deduce que se quiere subir un archivo */
		if (!empty ($_POST ["submit"]))
		{
			/* Comprueba el token */
			if (!hash_equals ($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				$GLOBALS ["contenido_principal"] = "Subida de archivos no autorizada.";
			}
			else
			{

				switch ($_FILES ["archivo"]["error"])
				{
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						$GLOBALS ["contenido_principal"]
							= "No se ha recibido"
							. " ningún archivo"
							. "<br />";
						break;

					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						$GLOBALS ["contenido_principal"]
							= "Archivo demasiado grande"
							. "<br />"
							. "El máximo aceptado es de "
							. ini_get ("upload_max_filesize")
							. "<br />";
						break;
					default:
						$GLOBALS ["contenido_principal"]
							= "Error al subir el archivo"
							. "<br />";
				}

				if (is_uploaded_file ($_FILES ["archivo"]["tmp_name"]))
				{
					$datos = file_get_contents (
							$_FILES ["archivo"]["tmp_name"]
					);

					$usuario = $_SESSION ["usuario"];
					$descr = $_POST ["descr"];

					$nombre = empty ($_POST ["nombre"])?
							null
							: $_POST ["nombre"];

					$permisos = ver_permisos (
							$_POST ["gid"]
							, $_POST ["resto"]
					);

					$GLOBALS ["contenido_principal"]
						= (insertar_archivo ($usuario,
								     $datos,
								     $descr,
								     $nombre,
								     $permisos)
						)?
						"Archivo subido con éxito"
						: "Error al subir el archivo";
				}
			}
		}
		else
		{
			$GLOBALS ["contenido_principal"] = $formulario;
		}
	}

	include $_SERVER ['DOCUMENT_ROOT'] . '/plantillas/miguel.php';
?>
