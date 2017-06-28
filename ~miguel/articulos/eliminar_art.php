<?php

	include $_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php";

	/* Comienza la sesión, si es necesario */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	$id_art = $_GET ["id_art"];
	$uid = $_GET ["uid"];

	eliminar_articulo ($id_art, $uid);
?>
