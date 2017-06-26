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
		$datos = json_decode (file_get_contents ($_SERVER['DOCUMENT_ROOT']
							. '/~miguel/datos-con_bd.json')
					, true);

		$bd = $datos ["bd"];
		$host = $datos ["host"];
		$usuario = $datos ["usuario"];
		$contr = $datos ["contr"];

		return pg_connect ("host=$host
				    dbname=$bd
				    user=$usuario
				    password=$contr");
	}


/* --------- */
/* ARTÍCULOS */
/* --------- */

	/**
	 * Obtiene el artículo con el id especificado.
	 *
	 * @param id_art
	 *		ID del artículo a obtener.
	 *
	 * @param usuario
	 *		ID del usuario propietario del artículo.
	 *
	 *
	 * @return
	 *		El texto del artículo, si se ha encontrado; o null si no se
	 *	ha podido encontrar o hubo algún fallo al conectar a la BDD.
	 */
	function obtener_articulo ($id_art, $usuario)
	{
		$texto = null;
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "obtener_articulo"
					, "SELECT * FROM articulos"
					. " WHERE id_articulo = $1"
					. " AND uid = $2");

		$consulta = pg_execute ($conn, "obtener_articulo"
					, array ($id_art, $usuario));

		/* Si se ha encontrado, se carga el texto */
		if (!$consulta || pg_num_rows ($consulta) != 1)
		{
			pg_close ($conn);
			return null;
		}

		$articulo = pg_fetch_array ($consulta);

		pg_close ($conn);
		return $articulo;
	}


	/**
	 * Guarda los datos en la tabla de los artículos de la base de datos.
	 *
	 * @param titulo
	 *		Título del artículo.
	 *
	 * @param texto
	 *		Cuerpo del artículo.
	 *
	 * @param categoria
	 *		Categoría del artículo.
	 *
	 * @param id_art
	 *		Identificador del artículo a insertar (si ya existía, se
	 * 	actualizan los datos.
	 *
	 * @param propiet
	 *		ID del usuario que escribe el artículo.
	 *
	 *
	 * @return
	 *		True si se añadió la tupla correctamente; o False si no.
	 */
	function insertar_articulo ($titulo, $texto, $categoria, $id_art, $propiet)
	{
		$conn = conectar ();
		if (!$conn)
		{
			return False;
		}

		$datos = array ("id_articulo" => $id_art
				, "titulo" => $titulo
				, "texto" => $texto
				, "categoria" => $categoria
				, "permisos" => "111010"
				, "fecha" => date ("Y-m-d")
				, "uid" => $propiet
		);

		$resultado = pg_insert ($conn, "articulos", $datos);

		if (!$resultado)
		{
			pg_close ($conn);
			return False;
		}

		pg_close ($conn);
		return True;

	}


	/**
	 * Busca un identificador libre para un nuevo artículo del usuario especificado.
	 *
	 * @param usuario
	 *		Usuario para el cual se quiere buscar un ID libre.
	 *
	 *
	 * @return
	 *		Un nuevo identificador que no se corresponde con ningún artículo,
	 *	o null si no se pudo conectar con la BDD.
	 */
	function buscar_id_libre_art ($usuario)
	{
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		$id = rand ();
		$consulta_str = "SELECT * FROM articulos "
			    . "WHERE id_articulo = $1 AND uid = $2";

		$consulta = pg_prepare ($conn
					, "buscar_id_libre_art"
					, $consulta_str);

		while (obtener_articulo ($id, $usuario) !== null)
		{
			$id = rand ();
		}

		pg_close ($conn);
		return $id;
	}


	/**
	 * Elimina un artículo de la base de datos y todos sus recursos asociados.
	 *
	 * @param id
	 *		ID del artículo.
	 *
	 * @param usuario
	 *		ID del usuario propietario del archivo.
	 *
	 *
	 * @return
	 *		True si se eliminó correctamente; o False si hubo algún error.
	 */
	function eliminar_articulo ($id, $usuario)
	{
		$res = obtener_recursos ($usuario, $id);

		while ($tupla = pg_fetch_array ($res))
		{
			eliminar_recurso ($tupla ["id_rec"], $id, $usuario);
		}

		$conn = conectar ();

		if (!$conn)
		{
			return False;
		}


		$datos = array (
			"id_articulo" => $id
			, "uid" => $usuario
		);
		$ret_val = pg_delete ($conn, "articulos", $datos);

		pg_close ($conn);
		return $ret_val;
	}

/* ------- */
/* CUENTAS */
/* ------- */

	/**
	 * Obtiene la cuenta del usuario especificado.
	 *
	 * @param nombre
	 *		ID del usuario cuya contraseña se desea obtener.
	 *
	 * @return
	 *		Array con los campos de la tupla resultado (si existe) de la
	 *	tabla 'usuarios': ['nombre', 'pass', 'uid']; o null si ha habido
	 *	algún problema.
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
		$consulta = pg_prepare ($conn
					, "ver_pass"
					, "SELECT * FROM usuarios WHERE uid = $1");
		$consulta = pg_execute ($conn, "ver_pass", array ($nombre));

		/* Si se ha encontrado, se carga la información */
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
	 *		Nombre de la cuenta.
	 *
	 * @param pass
	 *		Contraseña para la cuenta. Se debe proporcionar en
	 *	 texto plano para ser tratada en esta función.
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

		/* Intenta insertar los datos */
		$datos = array ("uid" => $nombre
				, "pass" => password_hash ($pass, PASSWORD_DEFAULT));
		$resultado = pg_insert ($conn, "usuarios", $datos);

		if (!$resultado)
		{
			pg_close ($conn);
			return False;
		}

		pg_close ($conn);
		return True;
	}

/* -------- */
/* ARCHIVOS */
/* -------- */

	/**
	 * Añade un archivo a la tabla "archivos" de la base de datos.
	 *
	 * @param propietario
	 *		ID del usuario propietario.
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
	function insertar_archivo ($propietario, $datos_archivo, $descr
				   , $nombre, $permisos)
	{
		/* Genera un id aleatorio que no esté ya en la base de datos */
		$id = rand ();
		do 
		{
			$id = rand ();
		} while (obtener_archivo ($id, $propietario) !== null);

		$conn = conectar ();
		if (!$conn)
		{
			return False;
		}

		/* Intenta insertar los datos */
		$datos_tupla = array ("id" => $id,
					"datos" => unpack ("H*", $datos_archivo) [1],
					"descr" => $descr,
					"permisos" => $permisos,
					"nombre" => $nombre,
					"uid" => $propietario
		);

		$resultado = pg_prepare ($conn, "insertar_arch"
						, "INSERT INTO archivos VALUES "
						. "($1, $2, $3, $4, $5, $6)");
		$resultado = pg_execute ($conn, "insertar_arch", $datos_tupla);

		if (!$resultado)
		{
			pg_close ($conn);
			return False;
		}

		pg_close ($conn);
		return True;
	}

	/**
	 * Obtiene un archivo de la base de datos. Si se quiere usar luego el campo
	 * "datos", hay que hacer una conversión para obtener la cadena que se insertó:
	 * pack ("H*", $tupla ["datos"])
	 *
	 * @param id
	 *		ID del archivo.
	 *
	 * @param usuario
	 *		ID del usuario propietario del archivo.
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
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_arch"
						, "SELECT * FROM archivos"
						. " WHERE id = $1 AND uid = $2");
		$consulta = pg_execute ($conn, "ver_arch", array ($id, $usuario));

		if ($consulta && (pg_num_rows ($consulta) == 1))
		{
			$tupla = pg_fetch_array ($consulta);
		}
		else
		{
			pg_close ($conn);
			return null;
		}

		pg_close ($conn);
		return $tupla;
	}


	/**
	 * Obtiene todos los archivos del usuario especificado
	 *
	 * @param usuario
	 *		ID del usuario cuyos archivos quieren ser recuperados.
	 *
	 *
	 * @return
	 *		Las tuplas con los datos; o null si no se encontró.
	 */
	function obtener_archivos ($usuario)
	{
		$salida = null;
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_archivos"
					, "SELECT * FROM archivos"
					. " WHERE uid = $1");
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
	 *		Las tuplas con los datos; o null si hubo algún error.
	 */
	function obtener_archivos_pub ()
	{
		$salida = null;
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_archivos_pub"
					, "SELECT * FROM archivos"
					. " WHERE permisos::text ~ '[01]{4}1[01]'");
		$consulta = pg_execute ($conn, "ver_archivos_pub", array ());

		if ($consulta)
		{
			$salida = $consulta;
		}

		pg_close ($conn);
		return $salida;
	}

	/**
	 * Elimina un archivo de la base de datos
	 *
	 * @param id
	 *		ID del archivo.
	 *
	 * @param usuario
	 *		ID del usuario propietario del archivo.
	 *
	 *
	 * @return
	 *		true si se eliminó correctamente; o false si hubo algún error.
	 */
	function eliminar_archivo ($id, $usuario)
	{
		$conn = conectar ();

		if (!$conn)
		{
			return False;
		}

		$datos = array (
			"id" => $id
			, "uid" => $usuario
		);
		$ret_val = pg_delete ($conn, "archivos", $datos);

		pg_close ($conn);
		return $ret_val;
	}

/* -------- */
/* RECURSOS */
/* -------- */

	/**
	 * Añade un archivo a la tabla "recursos" de la base de datos.
	 *
	 * @param articulo
	 *		ID del artículo al que pertenece el recurso.
	 *
	 * @param propietario
	 *		ID del usuario propietario.
	 *
	 * @param datos_archivo
	 *		Datos del archivo.
	 *
	 * @param tipo
	 *		Tipo de archivo (imagen, vídeo, gráfica..).
	 *
	 *
	 * @return
	 *		El ID del recurso, o -1 si no se pudo insertar en la BDD.
	 */
	function insertar_recurso ($articulo, $propietario, $datos_archivo, $tipo)
	{
		$conn = conectar ();
		if (!$conn)
		{
			return False;
		}


		/* Genera un id aleatorio que no esté ya en la base de datos */
		$consulta = pg_prepare ($conn, "ver_recurso_id"
					, "SELECT * FROM recursos"
					. " WHERE uid = $1"
					. " AND id_articulo = $2"
					. " AND id_rec = $3");
		$id = rand ();
		do 
		{
			$id = rand ();
			$args = array ($propietario, $articulo, $id);
			$consulta = pg_execute ($conn, "ver_recurso_id", $args);

		} while (pg_num_rows ($consulta) > 0 || ($id == -1));

		/* Intenta insertar los datos */
		$datos_tupla = array ("id_rec" => $id,
					"id_articulo" => $articulo,
					"datos" => unpack ("H*", $datos_archivo) [1],
					"tipo" => $tipo,
					"uid" => $propietario
		);

		$resultado = pg_prepare ($conn, "insertar_rec"
						, "INSERT INTO recursos VALUES "
						. "($1, $2, $3, $4, $5)");
		$resultado = pg_execute ($conn, "insertar_rec", $datos_tupla);

		/* Si no se pudo insertar, se comprueba que exista el artículo */
		if (!$resultado)
		{
			if ((obtener_articulo ($articulo, $propietario) === null)
			   && insertar_articulo ("", "", ""
						, $articulo, $propietario)
			)
			{
				return insertar_recurso ($articulo, $propietario
							, $datos_archivo, $datos_archivo
							, $tipo);
			}
			else
			{
				return -1;
			}
		}

		pg_close ($conn);
		return $id;
	}



	/**
	 * Obtiene un recurso específico perteneciente al usuario y artículo
	 * especificados.
	 *
	 * @param usuario
	 * 		Identificador del usuario propietario de los recursos.
	 *
	 * @param articulo
	 *		Identificador del artículo al que pertenece el recurso.
	 *
	 * @return
	 *		Un array con todas las tuplas obtenidas de la consulta, o null
	 *	si no se pudo obtener la información.
	 */
	function obtener_recurso ($usuario, $articulo, $recurso)
	{
		$salida = null;
		$conn = conectar ();
		$datos = array (
			"uid" => $usuario,
			"id_articulo" => $articulo,
			"id_rec" => $recurso
		);

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_recurso"
					, "SELECT * FROM recursos"
					. " WHERE uid = $1"
					. " AND id_articulo = $2"
					. " AND id_rec = $3");
		$consulta = pg_execute ($conn, "ver_recurso", $datos);

		if ($consulta && (pg_num_rows ($consulta) == 1) )
		{
			$salida = $consulta;
		}

		pg_close ($conn);
		return $salida;
	}

	/**
	 * Obtiene todos los recursos que pertenecen al usuario y artículo especificados.
	 *
	 * @param usuario
	 * 		Identificador del usuario propietario de los recursos.
	 *
	 * @param id_articulo
	 *		Identificador del artículo del que se quieren obtener los
	 *	recursos.
	 *
	 *
	 * @return
	 *		Un elemento de tipo pgresource con todas las tuplas obtenidas
	 *	de la consulta, o null si no se pudo obtener la información.
	 */
	function obtener_recursos ($usuario, $id_articulo)
	{
		$salida = null;
		$conn = conectar ();

		if (!$conn)
		{
			return null;
		}

		/* Prepara y ejecuta la consulta */
		$consulta = pg_prepare ($conn, "ver_recursos"
					, "SELECT * FROM recursos"
					. " WHERE uid = $1"
					. " AND id_articulo = $2");
		$consulta = pg_execute ($conn, "ver_recursos"
					, array ($usuario, $id_articulo)
		);

		if ($consulta)
		{
			$salida = $consulta;
		}

		pg_close ($conn);
		return $salida;
	}

	/**
	 * Elimina un recurso de la base de datos.
	 *
	 * @param id
	 *		ID del recurso.
	 *
	 *
	 * @param id_articulo
	 *		ID del artículo al que pertenece.
	 *
	 * @param usuario
	 *		ID del usuario propietario del recurso.
	 *
	 *
	 * @return
	 *		True si se eliminó correctamente; o False si hubo algún error.
	 */
	function eliminar_recurso ($id, $id_articulo, $usuario)
	{
		$conn = conectar ();

		if (!$conn)
		{
			return False;
		}

		$datos = array (
			"id_rec" => $id
			, "uid" => $usuario
			, "id_articulo" => $id_articulo
		);
		$ret_val = pg_delete ($conn, "recursos", $datos);

		pg_close ($conn);
		return $ret_val;
	}

?>
