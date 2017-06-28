<?php
	/* Carga el controlador para la base de datos */
	include_once ($_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php");

	/**
	 * Muestra la información relativa al artículo, cuya información se encuentra
	 * en la tupla pasada como argumento.
	 *
	 * @param tupla
	 *		Arrray asociativo con la información del artículo.
	 */
	function mostrar_info_articulo ($tupla)
	{
		$titulo = $tupla ["titulo"];
		$categoria = $tupla ["categoria"];
		$fecha = $tupla ["fecha"];

		$uid = $tupla ["uid"];
		$id_art = $tupla ["id_articulo"];

		$contenido = "";

		/* Comprueba si está públicamente disponible */
		if (!preg_match ("/^[01]{4}1[01]$/", $tupla ["permisos"]))
		{
			return;
		}

		/* Añade una lista al contenido principal  */
		$contenido .= "
			<ul class='info_articulo'>
				<li>Título: $titulo</li>
				<li>Categoría: $categoria</li>
				<li>Propietaria/o: $uid</li>
				<li>Última modificación: $fecha</li>
				<li><a "
				. "href='/~miguel/index.php?uid=$uid&id_art=$id_art'"
				. ">Ver</a></li>";

		/* Si se tiene permiso, se muestra el botón para editar el artículo,
		  ya sea porque es suyo o porque los permisos son del tipo xxxxx1 */
		if (isset ($_SESSION ["usuario"])
		    && ($_SESSION ["usuario"] == $uid)
		    || (preg_match ("/^[01]{5}1$/", $tupla ["permisos"]))
		)
		{
			$contenido .= "<li><a "
				. "href='/~miguel/articulos/editor.php"
					. "?uid=$uid&id_art=$id_art'"
				. ">Editar</a></li>";
		}

		$contenido .= "
			</ul>
		";

		/* Se inicializa la clave del array, si es necesario */
		if (!isset ($GLOBALS ["contenido_principal"]))
		{
			$GLOBALS ["contenido_principal"] = $contenido;
		}
		else
		{
			$GLOBALS ["contenido_principal"] .= $contenido;
		}
	}

	/* Carga los datos de la sesión actual (si es necesario) */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	/* Obtiene todos los artículos de la BDD */
	$res = obtener_articulos ();

	while ($tupla = pg_fetch_array ($res))
	{
		mostrar_info_articulo ($tupla);
	}

	/* Carga la plantilla */
	include $_SERVER ["DOCUMENT_ROOT"] . "/plantillas/miguel.php";
?>
