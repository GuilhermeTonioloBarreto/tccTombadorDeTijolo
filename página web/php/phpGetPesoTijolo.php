<?php
header('Content-Type: application/json');

$conn = new mysqli("127.0.0.1", "usuarioWeb", "usuarioWeb", "tombadordetijolo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o parâmetro 'limite' foi passado pela URL; define um valor padrão caso contrário.
$limite = isset($_GET['quantidade']) ? (int)$_GET['quantidade'] : 100;

// Query SQL com o limite especificado
$sql = "
    SELECT * FROM (
        SELECT * FROM pesotijolo
        ORDER BY dataregistro DESC
        LIMIT $limite
    ) AS subquery
    ORDER BY dataregistro ASC;
";
$result = $conn->query($sql);

// pegar dados do peso do tijolo
$idTijolo = [];
$pesoTijolo = [];
while ($row = $result->fetch_assoc()) {
    $idTijolo[] = $row['id'];
    $pesoTijolo[] = $row['peso'];
}

// média de peso de tijolo
$mediaPesos = array_sum($pesoTijolo) / count($pesoTijolo);

$data = [];
$data[] = $idTijolo;
$data[] = $pesoTijolo;
$data[] = $mediaPesos;
echo json_encode($data);

$conn->close();
?>
