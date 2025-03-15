create database if not exists Yume;
use Yume;

create table if not exists users (
	user_id int auto_increment not null primary key,
    nome varchar(50) not null,
    email varchar(50) not null unique,
    senha text not null,
    perfil blob,
    status_bd bit default 1
);

create table if not exists anime (
	anime_id int auto_increment not null primary key,
    titulo varchar(255) not null unique,
    descricao text not null,
    capa blob,
    qt_ep int not null,
    data_lanc date not null
);

create table if not exists genero (
	genero_id int auto_increment not null primary key,
    genero varchar(30) not null unique
);

create table if not exists adm (
	adm_id int auto_increment not null primary key,
    nome varchar(50) not null,
    email varchar(50) not null unique,
    senha text
);

create table if not exists noticia (
	noticia_id int auto_increment not null primary key,
    manchete text not null,
    url text not null,
    data_pub date
);

create table if not exists user_anime (
	cod_user int,
    cod_anime int,
    classificacao double default null,
    salvo bit default 0,
    foreign key (cod_user) references users (user_id),
    foreign key (cod_anime) references anime (anime_id)
);

create table if not exists anime_genero (
    cod_anime int,
    cod_genero int,
    foreign key (cod_anime) references anime (anime_id),
    foreign key (cod_genero) references genero (genero_id)
);

