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
</head>

<body>
	<!-- Panel superior con el título y la descripción -->
	<div id="titulo">
		<h1><a href="index.php">Título</a></h1>
	</div>

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
		<li class="elem_menu"><a href="#">Acceso</a></li>
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
		function obtener_art ($id_art)
		{
			$bd = "crispy_potato";
			$host = "localhost";
			$usuario = "postgres";
			$contr = "postgres";

			$texto = "## No se ha encontrado el artículo especificado";
			$conn = pg_connect ("host=$host dbname=$bd user=$usuario password=$contr");

			if (!$conn)
			{
				return "Error al conectarse a la base de datos.";
			}

			/* Prepara y ejecuta la consulta */
			$consulta = pg_prepare ($conn, "ver_art", "SELECT * FROM articulos WHERE id_articulo = $1");
			$consulta = pg_execute ($conn, "ver_art", array ($id_art));

			/* Si se ha encontrado, se carga el texto */
			if (!$consulta || pg_num_rows ($consulta) != 1)
			{
				$texto =  "## No se ha encontrado el artículo especificado";
			}
			else
			{
				$articulo = pg_fetch_array ($consulta);
				$texto = $articulo["texto"];
			}

			$Parsedown = new Parsedown ();

			$texto = $Parsedown->text ($texto);

			pg_close ($conn);
			return $texto;
		}

		/* Obtiene el artículo (subido con POST) */
		if (array_key_exists ("editor", $_POST))
		{
			echo $_POST["editor"];
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
