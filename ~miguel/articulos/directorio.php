<?php
	include_once ($_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php");

	/* Carga los datos de la sesión actual (si es necesario) */
	if (session_status () == PHP_SESSION_NONE)
	{
		session_start ();
	}

	/* Carga la plantilla */
	include $_SERVER ["DOCUMENT_ROOT"] . "/plantillas/miguel.php";
?>
