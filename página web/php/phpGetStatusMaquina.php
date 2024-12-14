<?php
header('Content-Type: application/json');

$conn = new mysqli("127.0.0.1", "usuarioWeb", "usuarioWeb", "tombadordetijolo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// obter data atualizada e do ano passado em epochtime
date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('Y-m-d') . " 23:59";
$dataAnoPassado = date('Y-m-d', strtotime('-1 year', strtotime($dataAtual))) . " 00:00";
$epochTimeDataAtual = strtotime($dataAtual);
$epochTimeAnoPassado = strtotime($dataAnoPassado);

// Query SQL com o limite especificado
$dataInicioEpochTime = isset($_GET['dataInicioEpochTime']) ? (int)$_GET['dataInicioEpochTime'] : $epochTimeAnoPassado;
$dataFimEpochTime = isset($_GET['dataFimEpochTime']) ? (int)$_GET['dataFimEpochTime'] : $epochTimeDataAtual;

$sql = "SELECT * FROM `maquinastatus` WHERE epochtimestamp >= $dataInicioEpochTime AND epochtimestamp <= $dataFimEpochTime";
$result = $conn->query($sql);

// pegar dados se está ligado/desligado e de quando que ligou/desligou
$ligadoDesligado = [];
$dataRegistro = [];
$epochTimestamp = [];
while ($row = $result->fetch_assoc()) {
    $ligadoDesligado[] = $row['ligadodesligado'];
    $dataRegistro[] = $row['dataregistro'];
    $epochTimestamp[] = $row['epochtimestamp'];
}

// pegar o status atual da máquina
$statusAtualMaquina = end($ligadoDesligado);

//englobar os dados e enviar via json
$data = [];
$data[] = $ligadoDesligado;
$data[] = $dataRegistro;
$data[] = $epochTimestamp;
$data[] = $statusAtualMaquina;
echo json_encode($data);

// encerra conexão com mysql
$conn->close();
?>

