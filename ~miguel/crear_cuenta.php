<?php
	include '../lib/db.php';

	/* Comienza la sesión, si es necesario */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	if (empty($_SESSION ["CSRFToken"]))
	{
		$_SESSION ["CSRFToken"] = bin2hex (random_bytes(32));
		$_SESSION ["registrado"] = False;
	}
	$token = $_SESSION ["CSRFToken"];

	$formulario = "<form id=\"login\" action=\"crear_cuenta.php\" method=\"post\" accept-charset=\"UTF-8\">
		<fieldset>
			<legend>Nueva cuenta</legend>
			<input type=\"hidden\" name=\"CSRFToken\" value=\"$token\">
			<p>
				<label for=\"usuario\">Nombre de usuario:</label>
				<input type=\"text\" name=\"usuario\" id=\"usuario\" maxlength=\"40\" />
			</p>
			<p>
				<label for=\"email\">Email:</label>
				<input type=\"text\" name=\"email\" id=\"email\" maxlength=\"100\" />
			</p>
			<p>
				<label for=\"password\" >Contraseña:</label>
				<input type=\"password\" name=\"pass\" id=\"password\" maxlength=\"72\" />
			</p>

			<input style=\"margin:5px\" type=\"submit\" name=\"submit\" value=\"Crear cuenta\" />
			<br/>
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
			/* Verifica que la cuenta no exista ya */
			$tupla = obtener_cuenta ($_POST ["email"]);
			$existe = !($tupla === null);

			/* Comprueba el token para evitar CSRF */
			if (hash_equals($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				if (!$existe)
				{
//					$_SESSION ["usuario"] = $tupla ["nombre"];
//					$_SESSION ["email"] = $_POST ["email"];
//					$_SESSION ["registrado"] = True;

					$GLOBAL ["contenido_principal"] = "aún no implementado...";
				}
				else
				{
					$GLOBAL ["contenido_principal"] = "Ya existe una cuenta con ese email <br/>" . $formulario;
				}
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
				<br/>Email: {$_SESSION ['email']}
				<br/>
				<a style=\"text-decoration: none;
						border:1px solid #5f5f5f;
						position: relative;
						top: 10px;
						padding: 5px;\" href=\"logout.php\">Salir</a>";
		}
	}

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
