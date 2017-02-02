/*
Created		26/01/2017
Modified		02/02/2017
Project		
Model			
Company		
Author		
Version		
Database		PostgreSQL 8.1 
*/



/* Drop Referential Integrity Triggers */





/* Drop User-Defined Triggers */



/* Drop Domains */



/* Drop Procedures */



/* Drop Views */



/* Drop Indexes */



/* Drop Tables */
Drop table if exists Grupos Restrict;
Drop table if exists Escribe Restrict;
Drop table if exists Archivos Restrict;
Drop table if exists Usuarios Restrict;
Drop table if exists Recursos Restrict;
Drop table if exists Articulos Restrict;



/* Create Domains */



/* Create Tables */


Create table Articulos
(
	id_articulo Bigint NOT NULL UNIQUE,
	titulo Varchar NOT NULL,
	texto Varchar NOT NULL,
	categoria Varchar Default 'Sin categoría',
	permisos Bit(6) NOT NULL Default B'111010',
 primary key (id_articulo)
) Without Oids;


Create table Recursos
(
	id_rec Bigint NOT NULL,
	id_articulo Bigint NOT NULL,
	datos Bytea NOT NULL,
	tipo Varchar Default 'Desconocido',
	permisos Bit(6) NOT NULL Default B'111010',
primary key (id_rec,id_articulo)
) Without Oids;


Create table Usuarios
(
	nombre Varchar NOT NULL Default 'Desconocido' UNIQUE,
	pass Varchar NOT NULL,
	uid Integer NOT NULL,
 primary key (nombre)
) Without Oids;


Create table Archivos
(
	id Bigint NOT NULL,
	propietario Varchar NOT NULL Default 'Desconocido',
	datos Bytea NOT NULL,
	descr Varchar Default 'Sin descripción',
	nombre Varchar Default 'Sin nombre',
	permisos Bit(6) NOT NULL Default B'111010',
 primary key (id,propietario)
) Without Oids;


Create table Escribe
(
	id_articulo Bigint NOT NULL,
	fecha Date NOT NULL,
	autor Varchar NOT NULL Default 'Desconocido',
 primary key (id_articulo,fecha,autor)
) Without Oids;


Create table Grupos
(
	usuario Varchar NOT NULL,
	grupo Varchar NOT NULL,
	gid Integer,
 primary key (usuario, grupo)
) Without Oids;



/* Create Tab 'Others' for Selected Tables */


/* Create Alternate Keys */



/* Create Indexes */



/* Create Foreign Keys */

Alter table Recursos add  foreign key (id_articulo) references Articulos (id_articulo) on update restrict on delete restrict;

Alter table Escribe add  foreign key (id_articulo) references Articulos (id_articulo) on update restrict on delete restrict;

Alter table Escribe add  foreign key (autor) references Usuarios (nombre) on update restrict on delete restrict;

Alter table Archivos add  foreign key (propietario) references Usuarios (nombre) on update restrict on delete restrict;

Alter table Grupos add  foreign key (usuario) references Usuarios (nombre) on update restrict on delete restrict;



/* Create Procedures */



/* Create Views */



/* Create Referential Integrity Triggers */





/* Create User-Defined Triggers */



/* Create Roles */



/* Add Roles To Roles */



/* Create Role Permissions */
/* Role permissions on tables */

/* Role permissions on views */

/* Role permissions on procedures */






