
<?php
	/* Biblioteca para el analizador de Markdown */
	include "../lib/Parsedown.php";

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
?>
