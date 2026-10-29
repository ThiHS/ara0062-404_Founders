<?php

try {
    require_once 'conec.php';

    echo "Conectou com o banco de dados";

} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados.";
}