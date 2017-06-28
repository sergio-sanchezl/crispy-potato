<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/db.php');

	/* Carga los datos de la sesión actual (si es necesario) */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	$GLOBALS ["contenido_principal"] = "<h2>Archivos en el servidor: </h2>";
	$texto = "<div id=\"ver_archivos\">";

	/* Obtiene los archivos disponibles para el usuario actual (tanto los
	 suyos como los de su grupo) */
	if (!empty ($_SESSION ["usuario"]))
	{
		$usuario = $_SESSION ["usuario"];

		/* Obtiene todos los archivos del usuario */
		$archivos = obtener_archivos ($usuario);

		if ($archivos)
		{
			$texto .= "<h3>Archivos del usuario: </h3>
					<ul class=\"lista_archivos\">";

			while ($tupla = pg_fetch_array ($archivos))
			{
				$texto .= "<li>Archivo " . $tupla ["id"]
					. "<ul>";
				$texto .= "	<li>Nombre: "
					. (($tupla ["nombre"] === null)?
						"Sin nombre"
						: $tupla ["nombre"]
					)
					. "</li>";
				$texto .= "	<li>Descripción: "
					. (($tupla ["descr"] === null)?
						"Sin descripción"
						: $tupla ["descr"]
					)
					. "</li>";
				$texto .= "	<li>Permisos: " . $tupla ["permisos"]
					. "</li>";

				$texto .= "<li style=\"list-style: none;\">"
					. "<a href='descargar.php?id=" . $tupla ["id"]
					. "&propiet=" . $usuario
					. "'> Descargar </a>"
					. "</li>";

				$texto .= "<li style=\"list-style: none;\">"
					. "<a href='eliminar.php?id=" . $tupla ["id"]
					. "&propiet=" . $usuario
					. "'> Eliminar </a>"
					. "</li>";


				$texto .= "</ul>
					 <br/>
					</li>";
			}

			$texto .= "</ul>";
		}
	}

	/* Obtiene los archivos públicos (permisos xxxxx1x) */
	$archivos = obtener_archivos_pub ();

	if ($archivos)
	{
		$texto .= "<h3>Archivos públicos:</h3>
				<ul class=\"lista_archivos\">";

		while ($tupla = pg_fetch_array ($archivos))
		{
			$texto .= "<li>Archivo " . $tupla ["id"]
				. "<ul>";
			$texto .= "	<li>Nombre: "
				. (($tupla ["nombre"] === null)?
					"Sin nombre"
					: $tupla ["nombre"]
				)
				. "</li>";
			$texto .= "	<li>Descripción: "
				. (($tupla ["descr"] === null)?
					"Sin descripción"
					: $tupla ["descr"]
				)
				. "</li>";
			$texto .= "	<li>Propietario: " . $tupla ["uid"]
				. "</li>";

			$texto .= "<li style=\"list-style: none;\">"
				. "<a href='descargar.php?id=" . $tupla ["id"]
				. "&propiet=" . $tupla ["uid"]
				. "'> Descargar </a>"
				. "</li>";

			$texto .= "</ul>
				<br/>
				</li>";
		}
		$texto .= "</ul>";
	}

	$texto .= "</div>";

	/* Finalmente, establece el contenido principal */
	$GLOBALS ["contenido_principal"] .= $texto;

	/* Carga la plantilla */
	include $_SERVER['DOCUMENT_ROOT'] . "/plantillas/miguel.php";
?>
