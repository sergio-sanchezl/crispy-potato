<?php
	include_once ($_SERVER ["DOCUMENT_ROOT"] . "/lib/db.php");

	if (!empty ($_GET ["id_rec"])
	    && !empty ($_GET ["id_art"])
	    && !empty ($_GET ["uid"])
	)
	{
		$rec = obtener_recurso ($_GET ["uid"]
					, $_GET ["id_art"]
					, $_GET ["id_rec"]
		);

		$tupla = pg_fetch_array ($rec);

		$cadena = ltrim ($tupla ["datos"], "\\x");
//		$datos = pack ("H*", $cadena);
		$datos = pack ("H*", pack ("H*", $cadena));

		header ("Content-type: applicaion/octet-stream");
		echo $datos;
	}
?>
