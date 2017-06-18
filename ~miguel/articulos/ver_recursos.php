<?php
	/* Carga el controlador para la base de datos */
	include $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";

	$uid = 1;

	$recursos = obtener_recursos ($uid);

	while ($tupla = pg_fetch_array ($recursos))
	{
		var_dump ($tupla);
	}
?>
