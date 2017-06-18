<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";

	/**
	 * Formatea el artículo para poder mostrarlo en la página principal.
	 *
	 * @param articulo
	 *		Tupla con la información necesaria para mostrar el artículo.
	 *
	 * @return
	 *		Una cadena con el HTML listo para ser insertado.
	 */
	function mostrar_articulo ($articulo)
	{
		$Parsedown = new Parsedown ();

		$html = "<span id='articulo_titulo'>"
			. $Parsedown->parse($articulo ['titulo'])
			. "</span>";

		$html .= "<span id='articulo_texto'>"
			. $Parsedown->text ($articulo ["texto"])
			. "</span>";

		return $html;
	}

	/* Si no se especifica un id válido, pone la página por defecto */
	$art_id = -7768;
	$uid = 2;

	/* Obtiene el id especificado (si lo hay) */
	if (array_key_exists ("art_id", $_GET)
	  && array_key_exists ("uid", $_GET))
	{
		$art_id = $_GET ["art_id"];
		$uid = $_GET ["uid"];
	}

	/* Lee el artículo de la base de datos */
	$articulo = obtener_articulo ($art_id, $uid);

	$GLOBAL ["contenido_principal"] = ($articulo === null)?
			"<h2 id='titulo'>Artículo no encontrado"
			: mostrar_articulo ($articulo)
	;


	/* Carga la plantilla */
	include $_SERVER['DOCUMENT_ROOT'] . "/plantillas/miguel.php";
?>
