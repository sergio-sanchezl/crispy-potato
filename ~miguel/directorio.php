<?php

	$GLOBAL ["contenido_principal"] = "
		<dl>
			<h2>Zonas principales</h2>
			<dt><a href=\"directorio.php\">Directorio</a></dt>
				<dd>Muestra todas las zonas de la página, con su descripción, y permite acceder a ellas.</dd>
			<br/>
			<dt><a href=\"login.php\">Acceso</a></dt>
				<dd>Sección para entrar a una cuenta y poder acceeder a algunas secciones que requieren identificación (como la de escribir artículos)</dd>

			<h2>Páginas externas</h2>
			<dt><a href=\"../\">Página principal</a></dt>
				<dd>Página principal con la presentación y enlaces a las secciones individuales</dd>
			<br/>
			<dt><a href=\"../~sergio/\">Sección de Sergio</a><dt>
				<dd>Sección de Sergio Sánchez López</dd>
		</dl>	
	";

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
