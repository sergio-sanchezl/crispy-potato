/*
Created		26/01/2017
Modified		26/01/2017
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
Drop table IF EXISTS Escribe Cascade;
Drop table IF EXISTS Archivos Cascade;
Drop table IF EXISTS Usuarios Cascade;
Drop table IF EXISTS Recursos Cascade;
Drop table IF EXISTS Articulos Cascade;



/* Create Domains */



/* Create Tables */


Create table Articulos
(
	id_articulo Bigint NOT NULL UNIQUE,
	Titulo Varchar NOT NULL,
	Texto Varchar NOT NULL,
	Categoria Varchar,
	Permisos Bit(6) NOT NULL Default B'111010',
 primary key (id_articulo)
) Without Oids;


Create table Recursos
(
	id_rec Bigint NOT NULL UNIQUE,
	id_articulo Bigint NOT NULL,
	datos Bytea NOT NULL,
	tipo Varchar Default 'Desconocido',
 primary key (id_rec,id_articulo)
) Without Oids;


Create table Usuarios
(
	Nombre Varchar NOT NULL,
	Pass Varchar NOT NULL,
 primary key (Nombre)
) Without Oids;


Create table Archivos
(
	id Bigint NOT NULL UNIQUE,
	propietario Varchar NOT NULL,
	Datos Bytea NOT NULL,
	Descr Varchar Default 'Sin descripción',
	Permisos Bit(6) NOT NULL Default B'111010',
 primary key (id)
) Without Oids;


Create table Escribe
(
	Nombre Varchar NOT NULL,
	id_articulo Bigint NOT NULL,
	fecha Date NOT NULL,
 primary key (Nombre,id_articulo,fecha)
) Without Oids;



/* Create Tab 'Others' for Selected Tables */


/* Create Alternate Keys */



/* Create Indexes */



/* Create Foreign Keys */

Alter table Recursos add  foreign key (id_articulo) references Articulos (id_articulo) on update restrict on delete restrict;

Alter table Escribe add  foreign key (id_articulo) references Articulos (id_articulo) on update restrict on delete restrict;

Alter table Escribe add  foreign key (Nombre) references Usuarios (Nombre) on update restrict on delete restrict;

Alter table Archivos add  foreign key (propietario) references Usuarios (Nombre) on update restrict on delete restrict;



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






