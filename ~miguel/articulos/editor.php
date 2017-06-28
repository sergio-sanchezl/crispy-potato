<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php";

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
		/* Añade el código para el editor en la cabecera del HTML */
		$GLOBALS ["incluir_head"] = "<script src=\""
						. "/tinymce/js/tinymce/tinymce.min.js\">"
						. "</script>\n";

		/* Si hay parámetros en la petición GET, comprueba si se trata de un
		artículo que hay que editar, o de uno que hay que crear */
		if (!empty ($_GET ["uid"])
		    && !empty ($_GET ["id_art"])
		)
		{
			$tupla = obtener_articulo ($_GET ["id_art"], $_GET ["uid"]);

			$categoria = $tupla ["categoria"];
			$titulo = $tupla ["titulo"];

			$texto = $tupla ["texto"];
			$texto = str_replace (chr (10), "", $texto);
			$texto = str_replace (chr (13), "", $texto);
			$texto = str_ireplace ('<','&#x200B;<',$texto);

			$html_editor = "
				<div id='editor_titulo'>
					Título: <input type='text' value='$titulo'>
				</div>
				<div id='editor_categ'>
					Categoría: <input type='text' value='$categoria'>
				</div>
				<form method='POST' action='./editor.php'>
					<textarea name='editor' id='editor'>"
					. $texto
					. "</textarea>
				</form>
				<script src='/~miguel/js/editor.js'></script>
			";

		}
		else
		{
			/* Añade valores a la sesión para saber el artículo que se está
			editando */
			if (!isset ($_SESSION ["id_articulo"]))
			{
				$_SESSION ["id_articulo"]
					= buscar_id_libre_art ($_SESSION ["usuario"]);

				$_SESSION ["uid_articulo"] = $_SESSION ["usuario"];

			}

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
		}

		$GLOBALS ["contenido_principal"] = $html_editor;
	}

	/* Carga la plantilla */
	include $_SERVER ["DOCUMENT_ROOT"] . "/plantillas/miguel.php";

?>
