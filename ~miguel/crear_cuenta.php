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
				<label for=\"nombre\">Nombre de usuario:</label>
				<input type=\"text\" name=\"nombre\" maxlength=\"40\" />
			</p>
			<p>
				<label for=\"password\" >Contraseña:</label>
				<input type=\"password\" name=\"pass\" maxlength=\"72\" />
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
			$tupla = obtener_cuenta ($_POST ["nombre"]);
			$existe = !($tupla === null);

			/* Comprueba el token para evitar CSRF */
			if (hash_equals($_SESSION ["CSRFToken"], $_POST ["CSRFToken"]))
			{
				if (!$existe)
				{
					if (!empty ($_POST ["nombre"]) && !empty ($_POST [""]))
					{
						$resultado = insertar_cuenta ($_POST ["nombre"], $_POST ["pass"]);

						$GLOBAL ["contenido_principal"] = ($resultado)?
							"Cuenta creada con éxito.<br/>
								<a tyle=\"text-decoration:none\" ref=\"login.php\">
								Intente acceder a su cuenta
								</a>"
							: "Error al crear la cuenta";
					}
					else
					{
						$GLOBAL ["contenido_principal"] = "Ni el nombre de usuario ni
										 la contraseña deben estar en
										 blanco <br/>" . $formulario;
					}
				}
				else
				{
					$GLOBAL ["contenido_principal"] = "Ya existe una cuenta con ese nombre <br/>" . $formulario;
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
