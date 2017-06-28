<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER ['DOCUMENT_ROOT'] . "/lib/db.php";

	$html_editor = "
		<div id='editor_titulo'>
			Título: <input type='text' value='Título'>
		</div>
		<div id='editor_categ'>
			Categoría: <input type='text' value='artículo'>
		</div>
		<form method='POST' action='./editor.php'>
			<textarea name='editor' id='editor'></textarea>
		</form>
		<script src='/~miguel/js/editor.js'></script>";

	/* Si es necesario, comienza la sesión */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	/* Si no se ha iniciado sesión no se permite el acceso */
	if (empty ($_SESSION ["usuario"]))
	{
		$GLOBALS ["contenido_principal"] = "Para acceder a esta página hay"
						. " que registrarse.<br /><a "
						. "href='/~miguel/cuentas/login.php'>"
						. "Pulse aquí</a> para acceder.";
	}
	else
	{
//		/* Si se recibe algo por POST es que se ha pulsado en 'guardar' y hay
//		 que actualizar la base de datos */
//		if (!empty ($_POST ["editor"]))
//		{
//			/* Añade el texto en el editor para no perder el progreso */
//			$html_editor .= $_POST ["editor"];
//		}

		$GLOBALS ["contenido_principal"] = $html_editor;

		/* Añade valores a la sesión para saber el artículo que se está
		editando */
		if (!isset ($_SESSION ["id_articulo"]))
		{
			$_SESSION ["id_articulo"]
				= buscar_id_libre_art ($_SESSION ["usuario"]);

			$_SESSION ["uid_articulo"] = $_SESSION ["usuario"];

		}

		/* Añade el código para el editor en la cabecera del HTML */
		$GLOBALS ["incluir_head"] = "<script src=\""
						. "/tinymce/js/tinymce/tinymce.min.js\">"
						. "</script>\n";
	}

	/* Carga la plantilla */
	include $_SERVER ['DOCUMENT_ROOT'] . "/plantillas/miguel.php";

?>
