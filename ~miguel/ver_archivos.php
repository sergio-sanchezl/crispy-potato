<?php
	include '../lib/db.php';

	/* Carga los datos de la sesión actual (si es necesario) y luego los borra */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$GLOBAL ["contenido_principal"] = "<h2>Archivos en el servidor: </h2>";
	$texto = "<div id=\"ver_archivos\"";

	/* Obtiene los archivos disponibles para el usuario actual (tanto los
	 suyos como los de su grupo) */
	if (!empty ($_SESSION ["usuario"]))
	{
		$usuario = $_SESSION ["usuario"];

		/* Obtiene todos los archivos del usuario */
		$archivos = ver_archivos ($usuario);

		if ($archivos)
		{
			$texto = "<h3>Archivos del usuario: </h3>
					<ul class=\"lista_archivos\">";

			while ($tupla = pg_fetch_array ($archivos))
			{
				$texto .= "<li>Archivo " . $tupla ["id"]
					. "<ul>";

				$texto .= "	<li>Nombre: " . $tupla ["nombre"] . "</li>";
				$texto .= "	<li>Descripción: " . $tupla ["descr"] . "</li>";
				$texto .= "	<li>Permisos: " . $tupla ["permisos"] . "</li>";

				$texto .= "</ul>
					<br/>
					</li>";
			}

			$texto .= "</ul>";
		}
	}

	/* Obtiene los archivos públicos (permisos xxxxx1x) */
	$archivos = ver_archivos_pub ();

	if ($archivos)
	{
		$texto .= "<h3>Archivos públicos</h3>
				<ul class=\"lista_archivos\">";

		while ($tupla = pg_fetch_array ($archivos))
		{
			$texto .= "<li>Archivo " . $tupla ["id"]
				. "<ul>";

			$texto .= "	<li>Nombre: " . $tupla ["nombre"] . "</li>";
			$texto .= "	<li>Descripción: " . $tupla ["descr"] . "</li>";
			$texto .= "	<li>Propietario: " . $tupla ["propietario"] . "</li>";

			$texto .= "</ul>
				<br/>
				</li>";
		}
		$texto .= "</ul>";
	}

	$texto .= "</div>";

	/* Finalmente, establece el contenido principal */
	$GLOBAL ["contenido_principal"] .= $texto;

	/* Carga la plantilla */
	include "../plantillas/miguel.php";
?>
