<?php
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
		/* Si se recibe algo por POST es que se ha pulsado en 'guardar' y hay que actualizar la base de datos */
		if (!empty ($_POST ["editor"]))
		{
			var_dump ($_POST);
		}
		else
		{
			$GLOBAL ["contenido_principal"] = $html_editor;
			$GLOBAL ["incluir_head"] = "<script src=\"https://cdn.ckeditor.com/4.6.2/full/ckeditor.js\"></script>";
		}
	}

	include $_SERVER['DOCUMENT_ROOT'] . '/plantillas/miguel.php';
?>
