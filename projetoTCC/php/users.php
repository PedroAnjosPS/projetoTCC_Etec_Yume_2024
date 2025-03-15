<?php

$GLOBALS['cnn'] = mysqli_connect("localhost", "root", "", "yume");

function getUserById($id) {
    $sqlCommand = "select * from users where id = $id";

    $result = mysqli_query($GLOBALS['cnn'], $sqlCommand);

    $data = mysqli_fetch_assoc($result);

    return $data;
}

function getUserIdByEmail($email) {
    $sqlCommand = "select user_id from users where name = $email";;

    $result = mysqli_query($GLOBALS['cnn'], $sqlCommand);

    $data = mysqli_fetch_assoc($result);

    return $data['user_id'];
}

function getUserSavedAnimes($id) {
    $sqlCommand = "select * from anime where anime_id IN(
        select cod_anime from user_anime where cod_user IN(
            select user_id from users where user_id = '$id'
        )
    );";

    $result = mysqli_query($GLOBALS['cnn'], $sqlCommand);

    $data = mysqli_fetch_assoc($result);

    return $data;
}

?>