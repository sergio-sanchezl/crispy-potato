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
	$titulo = $_POST ["titulo"];
	$categoria = strtolower ($_POST ["categ"]);

	if (!empty ($id_art)
	    && !empty ($uid)
	    && !empty ($datos)
	    && !empty ($titulo)
	    && !empty ($categoria)
	)
	{
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
