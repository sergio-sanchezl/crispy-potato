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

	<?php
		/* Inclusiones especiales (por ejemplo, para ckeditor en editor.php */
		if (!empty ($GLOBAL ["incluir_head"]))
		{
			echo "{$GLOBAL ['incluir_head']}";
		}
	?>
</head>

<body>
	<!-- Panel superior con el título y la descripción -->
	<div id="titulo">
		<h1><a href="index.php">Título</a></h1>
	</div>

	<!-- Panel con los detalles del usuario actual -->
	<?php
		/* Si es necesario, comienza la sesión */
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$estilo = "display:none";
		$detalle_usuario = "";

		/* Obtiene los detalles del usuario que esté actualmente registrado */
		if (!empty ($_SESSION["usuario"]))
		{
			$estilo = "display:inline-block";
			$detalle_usuario = $_SESSION ["usuario"] . "<br/>";
		}

		$detalle_usuario .= "<a href=\"logout.php\">Salir</a>";
	?>
	<div id="detalles_usuario" style=<?php echo $estilo; ?>><?php echo $detalle_usuario; ?></div>

	<!-- Flecha de navegación -->
	<div id="flecha_nav"></div>

	<!-- Panel superior con el menú -->
	<div class="boton_menu"></div>
	<ul class="menu">
		<li class="elem_menu"><a href="directorio.php">Directorio</a></li>
		<li class="parent elem_menu">Herramientas
			<ul class="contenido-desplegable">
			<?php
				/* Si se ha registrado, se muestran herramientas adicionales */
				if (session_status() == PHP_SESSION_NONE) {
					session_start();
				}

				if (!empty ($_SESSION ["usuario"]))
				{
					echo "<li><a href=\"editor.php\">Editor de artículos</a></li>
						<li><a href=\"subir_archivos.php\">Subir archivos</a></li>";
				}
			?>
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
			/* Si se ha definido la variable global "contenido_principal", se imprime aquí */
			if (array_key_exists ("contenido_principal", $GLOBAL))
			{
				echo $GLOBAL ["contenido_principal"];
			}
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
			<?php
				/* Si se ha registrado, se muestran secciones adicionales */
				if (session_status() == PHP_SESSION_NONE) {
					session_start();
				}

				if (!empty ($_SESSION ["usuario"]))
				{
					echo "<li class=\"elem_secc\"><a class=\"enlace_secc\" href=\"editor.php\">Editor de artículos</a></li>
						<li class=\"elem_secc\"><a class=\"enlace_secc\" href=\"subir_archivos.php\">Subir archivos</a></li>";
				}
			?>
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
