<?php
	session_start ();

	if (empty($_SESSION ["CSRFToken"]))
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes(32));
		$_SESSION ["registrado"] = False;
	}
	$token = $_SESSION ["CSRFToken"];

	$formulario = "<form id=\"login\" action=\"login.php\" method=\"post\" accept-charset=\"UTF-8\">
		<fieldset>
			<legend>Login</legend>
			<input type=\"hidden\" name=\"CSRFToken\" value=\"$token\">
			<p>
				<label for=\"username\">Nombre de usuario:</label>
				<input type=\"text\" name=\"usuario\" id=\"username\"  maxlength=\"35\" />
			</p>
			<p>
				<label for=\"email\">Email:</label>
				<input type=\"text\" name=\"email\" id=\"email\"  maxlength=\"100\" />
			</p>
			<p>
				<label for=\"password\" >Contraseña:</label>
				<input type=\"password\" name=\"pass\" id=\"password\" maxlength=\"250\" />
			</p>

			<input type=\"submit\" name=\"submit\" value=\"Aceptar\" />
		</fieldset>
	</form>";

	if (empty ($_POST ["submit"])
		&& (empty ($_SESSION ["registrado"]) || $_SESSION ["registrado"] == False))
	{
		$GLOBAL ["contenido_principal"] = $formulario;
	}
	else
	{
		if (empty ($_SESSION ["registrado"]) || $_SESSION ["registrado"] == False)
		{
			/* Comprueba el token para evitar CSRF */
			if (hash_equals($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				$_SESSION ["usuario"] = $_POST ["usuario"];
				$_SESSION ["email"] = $_POST ["email"];
				$_SESSION ["registrado"] = True;

				$GLOBAL ["contenido_principal"] = "Acceso autorizado correctamente";
			}
			else
			{
				/* Quizá habría que registrar el intento fallido en un log... */
				$GLOBAL ["contenido_principal"] = "Intento de acceso no autorizado";
			}
		}
		else
		{
			$GLOBAL ["contenido_principal"] = "Datos del usuario actual:
				<br/>Nombre: {$_SESSION ['usuario']}
				<br/>Email: {$_SESSION ['email']}";
		}
	}

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
