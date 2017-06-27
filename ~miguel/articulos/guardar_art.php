<?php
	/* Carga el controlador para la base de datos */
	include_once ($_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php");

	/* Si es necesario, comienza la sesión */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}


	$id_art = $_SESSION ["id_articulo"];
	$uid = $_SESSION ["uid_articulo"];
	$datos = $_POST ["datos"];


	if (isset ($id_art)
	    && isset ($uid)
	    && isset ($datos)
	)
	{
		$titulo = "";
		$categoria = "artículo";

		/* Intenta guardar el artículo */
		if (!guardar_articulo ($titulo, $datos, $categoria, $id_art, $uid))
		{
			header ("HTTP/1.0 500 Server Error");
		}
	}
	else
	{
		header ("HTTP/1.0 500 Server Error");
	}
?>
