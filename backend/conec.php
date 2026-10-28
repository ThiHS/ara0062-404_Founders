<?php

// CONEXÃO COM O BANCO DE DADOS
$host = "localhost";
$banco = "studytree";
$usuario = "root";
$senha = "";

$pdo = new PDO(
    "mysql:host=$host;dbname=$banco;charset=utf8mb4",
    $usuario,
    $senha,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);

// SELECT: VÁRIOS REGISTROS
function bd_select($sql, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

// SELECT: UM REGISTRO
function bd_select_one($sql, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch();
}

// INSERT
function bd_insert($sql, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $pdo->lastInsertId();
}

// UPDATE, DELETE E OUTROS COMANDOS
function bd_execute($sql, $params = []) {
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount();
}