DELIMITER $$

CREATE PROCEDURE selecionaAnimesGenSemel(IN param_anime_id INT)
BEGIN
	# Essa variavel vai armazenar o id do genero do param_anime_id que tem maior frequencia no banco
	DECLARE genero_frequente INT;
	
	#Seleciona o genero mais frequente dos generos do anime em questão
	SELECT g.genero_id
	INTO genero_frequente
	FROM genero g
	JOIN anime_genero ag ON g.genero_id = ag.cod_genero
	WHERE g.genero_id IN (
		SELECT cod_genero
		FROM anime_genero
		WHERE cod_anime = param_anime_id
	)
	GROUP BY g.genero_id
	ORDER BY COUNT(ag.cod_anime) DESC
	LIMIT 1;
	
	#Seleciona todas as informações dos 3 animes que tenham o genero mais frequente do anime em questão
	SELECT a.*
	FROM anime a 
	JOIN anime_genero ag ON a.anime_id = ag.cod_anime
	WHERE ag.cod_genero = genero_frequente
	AND a.anime_id != param_anime_id #Não seleciona as informações do anime de referencia
	ORDER BY RAND()
	LIMIT 3;
END $$

DELIMITER ;


