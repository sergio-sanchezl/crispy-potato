<?php
	/* Biblioteca para el analizador de Markdown */
	include $_SERVER['DOCUMENT_ROOT'] . "/lib/Parsedown.php";

	/**
	 * Conecta con la base de datos y devuelve el enlace.
	 *
	 * @return
	 *		La conexión con postgres
	 */
	function conectar ()
	{
		/* Obtiene los datos para la conexión del fichero 'datos-con_bd.json' */
		$datos = json_decode (file_get_contents ($_SERVER['DOCUMENT_ROOT'] . '/~miguel/datos-con_bd.json'), true);

		$bd = $datos ["bd"];
		$host = $datos ["host"];
		$usuario = $datos ["usuario"];
		$contr = $datos ["contr"];

		return pg_connect ("host=$host dbname=$bd user=$usuario password=$contr");
	}

	/**
	 * Obtiene el artículo con el id especificado.
	 *
	 * @param id_art
	 *		ID del artículo a obtener.
	 *
	 * @return
	 *		El texto del artículo, si se
	 *	ha encontrado; o un texto avisando del
	 *	error que se haya producido.
	 */
	function obtener_art ($id_art)
	{
		$texto = "## No se ha encontrado el artículo especificado";
		$conn = conectar ();

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

	/**
	 * Obtiene la cuenta del usuario especificado.
	 *
	 * @param nombre
	 *		Nombre del usuario cuya contraseña se desea obtener.
	 *
	 * @return
	 *		Array con los campos de la tupla resultado (si
	 *	existe) de la tabla 'usuarios': ['nombre', 'pass'];
	 *	o null si ha habido algún problema.
	 */
	function obtener_cuenta ($nombre)
	{
		$tupla = null;
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_pass", "SELECT * FROM usuarios WHERE nombre = $1");
		$consulta = pg_execute ($conn, "ver_pass", array ($nombre));

		/* Si se ha encontrado, se carga el nombre de usuario */
		if ($consulta && pg_num_rows ($consulta) == 1)
		{
			$tupla = pg_fetch_array ($consulta);
		}

		pg_close ($conn);
		return $tupla;
	}

	/**
	 * Añade un nuevo usuario a la base de datos.
	 *
	 * @param nombre
	 *		Nombre de la cuenta. Clave primaria (debe ser único).
	 *
	 * @param pass
	 *		Contraseña para la cuenta. Se debe proporcionar en
	 *	 texto plano para ser tratada (hasheada) en esta función.
	 *
	 *
	 * @return
	 *		True si la tupla se añadió correctamente, o False
	 *	si hubo algún problema.
	 */
	function insertar_cuenta ($nombre, $pass)
	{
		$conn = conectar ();

		if (!$conn)
		{
			return False;
		}

		/* Genera un id de usuario aleatorio */
		$id = rand ();

		$consulta = pg_prepare ($conn, "ver_uid", "SELECT * FROM usuarios WHERE id = $1");

		while (pg_num_rows (pg_execute ($conn, "ver_uid", array ($id)) > 0))
		{
			$id++;

			$consulta = pg_prepare ($conn, "ver_uid", "SELECT * FROM usuarios WHERE id = $1");
		}

		/* Intenta insertar los datos */
		$datos = array ("nombre" => $nombre, "pass" => password_hash ($pass, PASSWORD_DEFAULT), "uid" => $id);
		$resultado = pg_insert ($conn, "usuarios", $datos);

		if (!$resultado)
		{
			pg_close ();
			return False;
		}

		pg_close ($conn);
		return True;
	}

	/**
	 * Añade un archivo a la tabla "archivos" de la base de datos.
	 *
	 * @param propietario
	 *		Nombre del usuario propietario.
	 *
	 * @param datos_archivo
	 *		Datos del archivo.
	 *
	 * @param descr
	 *		Descripción del archivo (detalles, contenido...).
	 *
	 * @param nombre
	 *		Nombre para ayudar a identificar el archivo.
	 *
	 * @param permisos
	 *		Byte con los permisos de lectura y escritura. El formato
	 *	es el siguiente (empezando por el bit de mayor peso):
	 *		-> Permiso lectura usuario (UID)
	 *		-> Permiso escritura usuario (UID)
	 *
	 *		-> Permiso lectura grupo (GID)
	 *		-> Permiso escritura usuario (GID)
	 *
	 *		-> Permiso lectura rest de usuarios
	 *		-> Permiso escritura resto de usuarios
	 *
	 *	De modo gráfico: rw rw rw
	 *			 ^   ^  ^
	 *			 |   |  |
	 *			uid gid resto
	 *
	 * @return
	 *		True si se han insertado los datos correctamente; o False si no.
	 */
	function insertar_archivo ($propietario, $datos_archivo, $descr, $nombre, $permisos)
	{
		$conn = conectar ();

		if (!$conn)
		{
			return False;
		}

		/* Genera un id aleatorio que no esté ya en la base de datos */
		$id = rand ();
		do 
		{
			$id = rand ();
			$consulta = pg_prepare ($conn, "ver_arch", "SELECT * FROM "
						. "archivos WHERE id = $1 AND "
						. "propietario = $2");
		}
		while (
			pg_num_rows (
				pg_execute ($conn, "ver_arch", array ($id, $propietario))
			) > 0
		);

		/* Intenta insertar los datos */
		$datos_tupla = array ("id" => $id,
					"propietario" => $propietario,
					"datos" => $datos_archivo,
					"descr" => $descr,
					"nombre" => $nombre,
					"permisos" => $permisos
		);

		$consulta = pg_prepare ($conn, "insertar_arch", "INSERT INTO archivos VALUES ($1, $2, $3, $4, $5, $6::bit(6))");
		$resultado = pg_execute ($conn, "insertar_arch", $datos_tupla);

		if (!$resultado)
		{
			pg_close ();
			return False;
		}

		pg_close ($conn);
		return True;
	}

	/**
	 * Obtiene un archivo de la base de datos
	 *
	 * @param id
	 *		ID del archivo.
	 *
	 * @param usuario
	 *		Usuario propietario del archivo.
	 *
	 *
	 * @return
	 *		La tupla con los datos; o null si no se encontró.
	 */
	function obtener_archivo ($id, $usuario)
	{
		$tupla = null;
		$conn = conectar ();

		if (!$conn)
		{
			return "Error al conectarse a la base de datos.";
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_arch", "SELECT * FROM archivos WHERE id = $1 AND propietario = $2");
		$consulta = pg_execute ($conn, "ver_arch", array ($id, $usuario));

		if ($consulta && pg_num_rows ($consulta) == 1)
		{
			$tupla = pg_fetch_array ($consulta);
		}

		pg_close ($conn);
		return $tupla;
	}


	/**
	 * Obtiene todos los archivos del usuario especificado
	 *
	 * @param usuario
	 *		Nombre del usuario cuyos archivos quieren ser recuperados.
	 *
	 *
	 * @return
	 *		Las tuplas con los datos; o null si no se encontró.
	 */
	function ver_archivos ($usuario)
	{
		$conn = conectar ();
		$salida = null;

		if (!$conn)
		{
			return "Error al conectarse a la base de datos.";
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_archivos", "SELECT * FROM archivos WHERE propietario = $1");
		$consulta = pg_execute ($conn, "ver_archivos", array ($usuario));

		if ($consulta)
		{
			$salida = $consulta;
		}

		pg_close ($conn);
		return $salida;
	}

	/**
	 * Obtiene todos los archivos públicos (con permisos xxxx1x)
	 *
	 *
	 * @return
	 *		Las tuplas con los datos; o null si no se encontró.
	 */
	function ver_archivos_pub ()
	{
		$conn = conectar ();
		$salida = null;

		if (!$conn)
		{
			return "Error al conectarse a la base de datos.";
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_archivos_pub", "SELECT * FROM archivos WHERE permisos::text ~ '[01]{4}1[01]'");
		$consulta = pg_execute ($conn, "ver_archivos_pub", array());

		if ($consulta)
		{
			$salida = $consulta;
		}

		pg_close ($conn);
		return $salida;
	}

?>
