<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/db.php';

	/* Comienza la sesión, si es necesario */
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

	if (empty($_SESSION ["CSRFToken"]))
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes(32));
		$_SESSION ["registrado"] = False;
	}
	$token = $_SESSION ["CSRFToken"];

	$formulario = "<form id=\"login\" action=\"/~miguel/cuentas/login.php\" method=\"post\" accept-charset=\"UTF-8\">
		<fieldset>
			<legend>Login</legend>
			<input type=\"hidden\" name=\"CSRFToken\" value=\"$token\">
			<p>
				<label for=\"usuario\">Nombre:</label>
				<input type=\"text\" name=\"usuario\" id=\"usuario\"  maxlength=\"35\" />
			</p>
			<p>
				<label for=\"password\" >Contraseña:</label>
				<input type=\"password\" name=\"pass\" id=\"password\" maxlength=\"72\" />
			</p>

			<input style=\"margin:5px\" type=\"submit\" name=\"submit\" value=\"Aceptar\" />
			<br/>
			<a style=\"text-decoration:none\" href=\"/~miguel/cuentas/crear_cuenta.php\">Crear una cuenta</a>
		</fieldset>
	</form>";

	/* Si se recibe algo en "submit", se procesa; si no, se muestra el formulario */
	if (empty ($_POST ["submit"])
		&& (empty ($_SESSION ["registrado"]) || $_SESSION ["registrado"] == False))
	{
		$GLOBALS ["contenido_principal"] = $formulario;
	}
	else
	{
		if (empty ($_SESSION ["registrado"])
		   || $_SESSION ["registrado"] == False)
		{
			/* Se preparan todos los datos para intentar mitigar un ataque
			por tiempo (timing attack) */
			$tupla = obtener_cuenta ($_POST ["usuario"]);
			$auth = ($tupla === null)? False : password_verify ($_POST ["pass"], $tupla ["pass"]);

			/* Comprueba el token para evitar CSRF */
			if (hash_equals($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				if ($auth)
				{
					$_SESSION ["usuario"] = $tupla ["uid"];
					$_SESSION ["registrado"] = True;

					$GLOBALS ["contenido_principal"] = "Acceso autorizado correctamente";
				}
				else
				{
					$GLOBALS ["contenido_principal"] = "Nombre de usuario o contraseña incorrectos <br/>" . $formulario;
				}
			}
			else
			{
				/* Quizá habría que registrar el intento fallido en un log... */
				$GLOBALS ["contenido_principal"] = "Intento de acceso no autorizado";
			}
		}
		else
		{
			$GLOBALS ["contenido_principal"] = "Datos del usuario actual:
				<br/>Nombre: {$_SESSION ['usuario']}";
		}
	}

	/* Carga la plantilla */
	include $_SERVER['DOCUMENT_ROOT'] . "/plantillas/miguel.php";
?>
