select * from anime;

select * from users;

select genero from genero;

select * from users_anime order by cod_user;


# Exibe todas as informações e seus generos dos TODOS animes.

select anime.*, genero.genero from anime 
join anime_genero ag on cod_anime = anime_id
join genero on cod_genero = genero_id;

# ========================================== #


# Exibe todas as informações e seus generos do anime (pelo nome COMPLETO).

select anime.*, genero.genero from anime 
join anime_genero ag on cod_anime = anime_id
join genero on cod_genero = genero_id
WHERE anime.titulo = 'Kono Subarashii Sekai Ni Shukufuku Wo!'; # Substítuir pela respectivel variável

# ========================================== #


# Exibe todas as informações e seus generos do anime (pelo nome INCOMPLETO).

select anime.*, genero.genero from anime 
join anime_genero ag on cod_anime = anime_id
join genero on cod_genero = genero_id
WHERE anime.titulo LIKE '%Kono Subarashii%'; # Substítuir pela respectivel variável

# ========================================== #


# Exibe os animes salvos pelo usuário com id = ? #

SELECT * FROM anime WHERE anime_id IN(
	SELECT cod_anime FROM users_anime WHERE salvo = 1 AND cod_user IN(
		SELECT user_id FROM users WHERE user_id = 1 # Substítuir pela respectivel variável
	)
);

# ========================================== #


# Exibe os animes salvos pelo usuário (pelo id) #

SELECT * from anime where anime_id IN(
	select cod_anime from users_anime where cod_user IN(
		select user_id from users where user_id = 1 # Substítuir pela respectivel variável
	)
);

# ========================================== #


# Exibe a classificacao do anime (pelo nome) #

SELECT AVG(classificacao) AS pontuacao FROM users_anime WHERE cod_anime IN(
	SELECT anime_id FROM anime WHERE titulo = 'Kono Subarashii Sekai Ni Shukufuku Wo!' # Substítuir pela respectivel variável
);

# ========================================== #


# Exibe a classificacao do anime (pelo id) #

SELECT AVG(classificacao) AS pontuacao FROM users_anime WHERE cod_anime IN(
	SELECT anime_id FROM anime WHERE anime_id = 1 # Substítuir pela respectivel variável
);

# ========================================== #


