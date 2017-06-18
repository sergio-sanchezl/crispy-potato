<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";

	$html_editor = "<form method=\"POST\" action=\"./editor.php\">
			<textarea name=\"editor\"></textarea>
		</form>
		<script src=\"/~miguel/js/editor.js\"></script>";

	/* Si es necesario, comienza la sesión */
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

	/* Si no se ha iniciado sesión no se permite el acceso */
	if (empty ($_SESSION ["usuario"]))
	{
		$GLOBAL ["contenido_principal"] = "Para acceder a esta página hay que registrarse.
							<br/><a href=\"/~miguel/cuentas/login.php\">Pulse aquí</a> para acceder.";
	}
	else
	{
		/* Si se recibe algo por POST es que se ha pulsado en 'guardar' y hay
		 que actualizar la base de datos */
		if (!empty ($_POST ["editor"]))
		{
			/* Añade el texto en el editor para no perder el progreso */
			$html_editor .= "<script>CKEDITOR.instances[\"editor\"].setData"
					. "(`" . $_POST ["editor"] . "`)</script>";
		}

		$GLOBAL ["contenido_principal"] = $html_editor;

		/* Añade el código para el editor */
		$GLOBAL ["incluir_head"] = "<script src=\"" 
					    . "/tinymce/js/tinymce/tinymce.min.js\">"
					    . "</script>"
					    . "<script>"
					    . "tinymce.init({ selector:'textarea' });"
					    . "</script>";
	}

	/* Carga la plantilla */
	include $_SERVER ['DOCUMENT_ROOT'] . "/plantillas/miguel.php";

?>
