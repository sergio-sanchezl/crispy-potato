<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Sección de Miguel</title>

	<script src="https://code.jquery.com/jquery-3.1.1.min.js"
		integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
		crossorigin="anonymous">
	</script>

	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />


	<link rel="stylesheet" href="css/theme.css">

	<link rel="stylesheet" media="(max-width: 639px)" href="css/layout-mobile.css">
	<link rel="stylesheet" media="(min-width: 640px)" href="css/layout.css">

	<script src="js/script.js"></script>
<body>
	<!-- Panel superior con el título y la descripción -->
	<div id="titulo">
		<h1><a href="index.php">Título</a></h1>
	</div>

	<!-- Panel con los detalles del usuario actual -->
	<?php
		session_start ();

                $estilo = "display:none";
                $contenido = "";

                if (!empty ($_SESSION ["usuario"]))
                {
                        $estilo = "display:inline-block";
                        $contenido = $_SESSION ["usuario"] . "<br />";
                }

                $contenido .= "<a href=\"logout.php\">Salir</a>";
        ?>
        <div id="detalles_usuario" style=<?php echo $estilo; ?>><?php echo $contenido; ?></div>


	<!-- Flecha de navegación -->
	<div id="flecha_nav"></div>

	<!-- Panel superior con el menú -->
	<div class="boton_menu"></div>
	<ul class="menu">
		<li class="elem_menu"><a href="#">Directorio</a></li>
		<li class="parent elem_menu">Herramientas
			<ul class="contenido-desplegable">
			</ul>
		</li>
		<li class="elem_menu"><a href="login.php">Acceso</a></li>
		<li class="elem_menu"><a href="#">Contacto</a></li>
		<li class="parent elem_menu">Idioma
			<ul class="contenido-desplegable">
				<li><a href="#">Español</a></li>
				<li><a href="#">Inglés</a></li>
			</ul>
		</li>
	</ul>

	<!-- Panel central con el contenido principal -->
	<div id="principal">
		<?php
			/* No hay llamada a session_start() porque ya se ha hecho más arriba (en #detalles_usuario) */
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
				echo $formulario;
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

						echo "Acceso autorizado correctamente
							<style>
								#detalles_usuario {display: inline-block}
							</style>";
					}
					else
					{
						/* Quizá habría que registrar el intento fallido en un log... */
						echo "Intento de acceso no autorizado";
					}
				}
				else
				{
					echo "Datos del usuario actual:
						<br/>Nombre: {$_SESSION ['usuario']}
						<br/>Email: {$_SESSION ['email']}";
				}
			}

//echo "<br />Sesión: "; var_dump ($_SESSION);
//echo "<br />Post: "; var_dump ($_POST);
		?>
	</div>

	<!-- Panel izquierdo con las secciones -->
	<div id="boton_secc"></div>
	<div id="secciones">
		<ul id="lista_secc" >
			<li class="elem_secc prim_elem"><a class="enlace_secc" href="#">Herramientas</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">Artículos</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">Tutoriales</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">Blog</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">Proyectos</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">CTF's</a></li>
			<li class="elem_secc"><a class="enlace_secc" href="#">Enlaces de interés</a></li>
		</ul>
	</div>

	<!-- Pie de la página -->
	<footer id="pie_pagina">
		<div class="bloque_pie">
			<!-- Enlaces cruzados a esta página (enlaces internos) -->
			<a href="../">Página principal</a>
			<p>
			<a href="../~sergio/index.html">Sección de Sergio</a>
		</div>

		<div class="barra_bloque_pie"></div>

		<div class="bloque_pie">
			<!-- Bloque para enlaces al código fuente, redes, contacto, etc. (enlaces externos) -->
			<p>
			Contacto: <a href="mailto:miguel.garciamartin@hotmail.com">miguel.garciamartin@hotmail.com</a>
			</p>

			<p>
			<a href="https://github.com/Foo-Manroot/crispy-potato">Código en Github</a>
			</p>

			<p>
			<a href="https://github.com/Foo-Manroot"><img src="../resources/GitHub-Mark-Light-32px.png"/></a>
			</p>
		</div>
	</footer>
</body>

</html>
