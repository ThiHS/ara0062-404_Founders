<?php
// Recebe o formulário de cad_curso.html

require_once __DIR__ . "/tratareq.php";
exigir_post();

// variáveis para o retorno do HTML vindo do servidor.

$voltar = "cad_curso.html";
$nomePost = $_POST["nome"] ?? "";
$descPost = $_POST["desc"] ?? "";
$idAtualizarPost = $_POST["id_curso"] ?? "";
$idExcluirPost = $_POST["excluir"] ?? "";

// Valida os dados recebidos do formulário, 
// garantindo que sejam strings e removendo espaços em branco e não sejam numeros quebrados 
// ou negativos.

$nome = is_string($nomePost) ? trim($nomePost) : "";
$desc = is_string($descPost) ? trim($descPost) : "";
if (is_string($idAtualizarPost) && ctype_digit($idAtualizarPost)) {
    $idAtualizar = (int)$idAtualizarPost;
} else {
    $idAtualizar = 0;
}
if (is_string($idExcluirPost) && ctype_digit($idExcluirPost)) {
    $idExcluir = (int)$idExcluirPost;
} else {
    $idExcluir = 0;
}

$ok = true;
$mensagem = "";

// Trata os dados dos cursos os vínculos relacionados são tratados pelas regras do banco.
// na parte ON CASCADE ON DELETE, que exclui automaticamente os
// registros relacionados nas tabelas de vínculos do curso.
try {
    if (isset($_POST["excluir"])) {
        if ($idExcluir <= 0) {
            $ok = false;
            $mensagem = "Informe um ID de curso válido para excluir.";
        } else {
            $linhas = bd_execute("DELETE FROM cursos WHERE id_curso = ?", [$idExcluir]);
            if ($linhas > 0) {
                $mensagem = "curso excluído com sucesso.";
            } else {
                $ok = false;
                $mensagem = "nenhum curso foi excluído.";
            }
        }
    } elseif (isset($_POST["id_curso"])) {
        if ($idAtualizar <= 0) {
            $ok = false;
            $mensagem = "Informe um ID de curso válido para atualizar.";
        } elseif ($nome === "" || mb_strlen($nome) > 80) {
            $ok = false;
            $mensagem = "nome do curso com máximo de 80 caracteres.";
        } elseif (mb_strlen($desc) > 300) {
            $ok = false;
            $mensagem = "descrição deve ter no máximo 300 caracteres.";
        } else {
            $cursoExiste = bd_select_one(
                "SELECT id_curso FROM cursos WHERE id_curso = ?",
                [$idAtualizar]
            );
            $nomeExiste = bd_select_one(
                "SELECT id_curso FROM cursos WHERE nome = ? AND id_curso <> ?",
                [$nome, $idAtualizar]
            );

            if (!$cursoExiste) {
                $ok = false;
                $mensagem = "curso não encontrado.";
            } elseif ($nomeExiste) {
                $ok = false;
                $mensagem = "ja tem outro curso com esse nome.";
            } else {
                bd_execute(
                    "UPDATE cursos SET nome = ?, descricao = ? WHERE id_curso = ?",
                    [$nome, $desc !== "" ? $desc : null, $idAtualizar]
                );
                $mensagem = "curso atualizado.";
            }
        }
    } else {
        if ($nome === "" || mb_strlen($nome) > 80) {
            $ok = false;
            $mensagem = "Informe o nome do curso (até 80 caracteres).";
        } elseif (mb_strlen($desc) > 300) {
            $ok = false;
            $mensagem = "a descrição do curso deve ter no máximo 300 caracteres.";
        } else {
            $existe = bd_select_one(
                "SELECT id_curso FROM cursos WHERE nome = ?",
                [$nome]
            );

            if ($existe) {
                $ok = false;
                $mensagem = "ja existe um curso com esse nome.";
            } else {
                bd_insert(
                    "INSERT INTO cursos (nome, descricao) VALUES (?, ?)",
                    [$nome, $desc !== "" ? $desc : null]
                );
                $mensagem = "curso cadastrado.";
            }
        }
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    $ok = false;
    $mensagem = "Erro ao processar o curso.";
}

// Prepara a mensagem para inclusão segura na página HTML, 
// evitando HTML injection com má interpretação de aspas caracteres especiais.
if ($ok) {
    $titulo = "Sucesso";
} 
 else {
    $titulo = "Erro";
}
$msg = htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8");
$link = htmlspecialchars($voltar, ENT_QUOTES, "UTF-8");
if ($ok) {
    http_response_code(200);
} else {
    http_response_code(400);
}
echo "<!DOCTYPE html>
         <html lang='pt-br'>
            <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>StudyTree</title>
            </head>
            <body>
              <h2>$titulo</h2>
               <p>$msg</p>
               <a href='$link'>Voltar</a>
            </body>
         </html>";
