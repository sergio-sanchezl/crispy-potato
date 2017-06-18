/*
Created		26/01/2017
Modified		16/05/2017
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
Drop table "pertenece" Cascade;
Drop table "grupos" Cascade;
Drop table "archivos" Cascade;
Drop table "usuarios" Cascade;
Drop table "recursos" Cascade;
Drop table "articulos" Cascade;



/* Create Domains */



/* Create Tables */


Create table "articulos"
(
	"id_articulo" Bigint NOT NULL,
	"titulo" Varchar NOT NULL,
	"texto" Varchar NOT NULL,
	"categoria" Varchar Default 'Sin clasificar',
	"permisos" Bit(6) NOT NULL Default B'111010',
	"fecha" Date NOT NULL,
	"uid" Integer NOT NULL,
 primary key ("id_articulo","uid")
) Without Oids;


Create table "recursos"
(
	"id_rec" Bigint NOT NULL,
	"id_articulo" Bigint NOT NULL,
	"datos" Bytea NOT NULL,
	"tipo" Varchar Default 'Desconocido',
	"uid" Integer NOT NULL,
 primary key ("id_rec","id_articulo","uid")
) Without Oids;


Create table "usuarios"
(
	"usuario" Varchar NOT NULL,
	"pass" Varchar NOT NULL,
	"uid" Integer NOT NULL,
 primary key ("uid")
) Without Oids;


Create table "archivos"
(
	"id" Bigint NOT NULL,
	"datos" Bytea NOT NULL,
	"descr" Varchar Default 'Sin clasificar',
	"permisos" Bit(6) NOT NULL Default B'111010',
	"nombre" Varchar Default 'Sin nombre',
	"uid" Integer NOT NULL,
 primary key ("id","uid")
) Without Oids;


Create table "grupos"
(
	"gid" Integer NOT NULL,
	"nombre" Varchar NOT NULL,
 primary key ("gid")
) Without Oids;


Create table "pertenece"
(
	"uid" Integer NOT NULL,
	"gid" Integer NOT NULL,
 primary key ("uid","gid")
) Without Oids;



/* Create Tab 'Others' for Selected Tables */


/* Create Alternate Keys */



/* Create Indexes */



/* Create Foreign Keys */

Alter table "recursos" add  foreign key ("id_articulo","uid") references "articulos" ("id_articulo","uid") on update restrict on delete restrict;

Alter table "archivos" add  foreign key ("uid") references "usuarios" ("uid") on update restrict on delete restrict;

Alter table "articulos" add  foreign key ("uid") references "usuarios" ("uid") on update restrict on delete restrict;

Alter table "pertenece" add  foreign key ("uid") references "usuarios" ("uid") on update restrict on delete restrict;

Alter table "pertenece" add  foreign key ("gid") references "grupos" ("gid") on update restrict on delete restrict;



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






