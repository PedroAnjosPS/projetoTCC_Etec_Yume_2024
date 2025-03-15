use yume;

###################### Genres inserts ##################################

insert into genero (genero) VALUES
	('Comédia'),
	('Fantasia'),
	('Ação'),
	('Romance'),
	('Supernatura'),
	('Ecchi'),
	('Slice of Life'),
	('Shounen'),
	('Harem'),
	('Aventura');
	
###########################################################################

###################### Users inserts ######################################
insert into users (nome, email, senha, adm) values
	('Olavo', 'olavo@gmail.com', '12345', b'0'),
	('PedroPHP', 'php@gmail.com', '132', b'0'),
	('Miguel Santana', 'miguelito@gmail.com', '3214', b'0'),
	('OlavoAdm', 'olavo.adm@gmail.com', '12345', b'1'),
	('PedroPHPAdm', 'php.adm@gmail.com', '132', b'1'),
	('Miguel SantanaAdm', 'miguelito.adm@gmail.com', '3214', b'1');

###########################################################################
