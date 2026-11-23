<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "daw";

header("Content-Type: application/json; charset=utf-8");

// No PHP 8.1+ o mysqli lança exceção em caso de erro; desligando, o "if ($conn->connect_error)" funciona
mysqli_report(MYSQLI_REPORT_OFF);

// Cria a conexão com o banco
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se conectou
if ($conn->connect_error) {
    echo json_encode(["sucesso" => false, "mensagem" => "Falha na conexão: " . $conn->connect_error], JSON_UNESCAPED_UNICODE);
    exit;
}

$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["sucesso" => false, "mensagem" => "Use POST."], JSON_UNESCAPED_UNICODE);
    exit;
}

$sigla        = strtoupper(trim($_POST["sigla"] ?? ""));
$nome         = trim($_POST["nome"] ?? "");
$cargaHoraria = trim($_POST["carga_horaria"] ?? "");

if ($sigla == "" || $nome == "" || $cargaHoraria == "") {
    echo json_encode(["sucesso" => false, "mensagem" => "Preencha sigla, nome e carga horária."], JSON_UNESCAPED_UNICODE);
    exit;
}

if (strlen($sigla) > 10) {
    echo json_encode(["sucesso" => false, "mensagem" => "A sigla pode ter no máximo 10 caracteres."], JSON_UNESCAPED_UNICODE);
    exit;
}

if (filter_var($cargaHoraria, FILTER_VALIDATE_INT) === false || $cargaHoraria <= 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "A carga horária deve ser um número inteiro maior que zero."], JSON_UNESCAPED_UNICODE);
    exit;
}

// Verifica se a sigla já existe
$stmt = $conn->prepare("SELECT id FROM disciplinas WHERE sigla = ?");
$stmt->bind_param("s", $sigla);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["sucesso" => false, "mensagem" => "Já existe uma disciplina com a sigla $sigla."], JSON_UNESCAPED_UNICODE);
    exit;
}
$stmt->close();

// Insere a disciplina
$stmt = $conn->prepare("INSERT INTO disciplinas (sigla, nome, carga_horaria) VALUES (?, ?, ?)");
$cargaHoraria = (int) $cargaHoraria;
$stmt->bind_param("ssi", $sigla, $nome, $cargaHoraria);

if ($stmt->execute()) {
    echo json_encode([
        "sucesso"    => true,
        "mensagem"   => "Disciplina incluída com sucesso!",
        "disciplina" => [
            "id"            => $conn->insert_id,
            "sigla"         => $sigla,
            "nome"          => $nome,
            "carga_horaria" => $cargaHoraria
        ]
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao incluir: " . $stmt->error], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
?>