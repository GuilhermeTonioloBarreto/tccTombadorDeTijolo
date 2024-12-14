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

$sql = "SELECT * FROM `tijoloclassificacao` WHERE epochtimestamp >= $dataInicioEpochTime AND epochtimestamp <= $dataFimEpochTime";
$result = $conn->query($sql);

// cria array que adiciona 1 quando o tijolo foi aprovado e retira 1 quando o tijolo foi reprovado
$classificacao = [0];
while ($row = $result->fetch_assoc()) {
    if($row['aprovado'] == '1' &&  $row['reprovado'] == '0'){
        $classificacao[] = end($classificacao) + 1;
    } else if($row['aprovado'] == '0' &&  $row['reprovado'] == '1'){
        $classificacao[] = end($classificacao) - 1;
    }
}

// cria array de eixo X para o array classificacao
$eixoX = range(0, count($classificacao) - 1);

// exibe o valor atual dos tijolos aprovados
$numeroTijolosAprovados = end($classificacao);

$data = [];
$data[] = $eixoX;
$data[] = $classificacao;
$data[] = $numeroTijolosAprovados;

//englobar os dados e enviar via json
echo json_encode($data);

// encerra conexão com mysql
$conn->close();
?>

