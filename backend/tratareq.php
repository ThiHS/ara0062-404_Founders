<?php
// constante __DIR__ retorna o caminho completo do diretório do arquivo atual,
// mesmo que o script seja chamado de outro diretório.
// require_once exige que a conexao seja estabelecida antes de qualquer outra coisa
require_once __DIR__ . "/conec.php";

// Garante que a página só seja acessada via POST, caso contrário retorna erro 405.
// evita HTML injection e outros problemas de segurança.
function exigir_post() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        exit("Requisição com metodo errado.");
    }
}

