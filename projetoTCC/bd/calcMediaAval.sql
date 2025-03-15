DELIMITER $$

DROP PROCEDURE IF EXISTS calcularMediaAval$$

CREATE PROCEDURE calcularMediaAval(IN param_cod_anime INT)
BEGIN
	#RETORNA A MEDIA EM PORCENTAGEM (CADA UMA VALE 20)
	SELECT IFNULL(AVG(classificacao) * 20, 0) AS media_porcentagem
	FROM users_anime
	WHERE cod_anime = param_cod_anime;
END$$