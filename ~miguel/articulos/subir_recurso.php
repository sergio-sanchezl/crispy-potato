<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php";

	/* Comienza la sesión, si es necesario */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	$accepted_origins = array (
			"http://localhost"
			, "http://192.168.1.1"
			, "http://example.com"
	);

	reset ($_FILES);
	$temp = current ($_FILES);


	/* Comprueba si el archivo se subió correctamente */
	switch ($temp ["error"])
	{
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			header("HTTP/1.0 500 Server Error");
			break;

		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			header("HTTP/1.0 500 Server Error");
			break;

		default:
			header("HTTP/1.0 500 Server Error");
	}


	if (is_uploaded_file ($temp ["tmp_name"]))
	{
		if (isset ($_SERVER ["HTTP_ORIGIN"]))
		{
			/* Comprueba el origen de la petición */
			if (in_array ($_SERVER ["HTTP_ORIGIN"], $accepted_origins))
			{
				header ("Access-Control-Allow-Origin: "
					. $_SERVER ["HTTP_ORIGIN"]);
			}
			else
			{
				header ("HTTP/1.0 403 Origin Denied");
				return;
			}
		}

		/* Sanitiza la entrada */
		if (preg_match ("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/",
			$temp["name"])
		)
		{
			header ("HTTP/1.0 500 Invalid file name.");
			return;
		}


		/* Inserta el recurso en la BDD */
		$id_articulo = $_SESSION ["id_articulo"];
		$tipo = isset ($_POST ["tipo"])? $_POST ["tipo"] : "Desconocido";
		$datos = file_get_contents ($temp ["tmp_name"]);
		$usuario = $_SESSION ["usuario"];

		$res = insertar_recurso ($id_articulo,
					 $usuario,
					 $datos,
					 $tipo);

		if ($res != -1)
		{
			echo json_encode (
				array (
					"location" => "/~miguel/articulos/"
							. "ver_recurso.php"
							. "?id_rec=" . $res
							. "&id_art=" . $id_articulo
							. "&uid=" . $usuario
				)
			);
		}
		else
		{
			header ("HTTP/1.0 500 Server Error");
		}
	}
	else
	{
		/* La carga falló */
		header ("HTTP/1.0 500 Server Error");
	}
?>
