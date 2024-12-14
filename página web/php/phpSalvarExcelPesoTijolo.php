<?php 
// Inclui a biblioteca PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Conexão com o banco de dados MySQL
$conn = new mysqli("127.0.0.1", "usuarioWeb", "usuarioWeb", "tombadordetijolo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$limite = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 100;

// Query SQL para extrair os dados
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

// Cria uma nova planilha
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Adiciona os cabeçalhos das colunas
$colunas = ['ID', 'Peso', 'Data do registro'];
$sheet->fromArray($colunas, NULL, 'A1');

// Adiciona os dados do MySQL à planilha
if ($result->num_rows > 0) {
    $linha = 2; // Começa na segunda linha (abaixo dos cabeçalhos)
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $linha, $row['id']);
        $sheet->setCellValue('B' . $linha, $row['peso']);
        $sheet->setCellValue('C' . $linha, $row['dataregistro']);
        $linha++;
    }
}

// Fecha a conexão com o banco de dados
$conn->close();

// Define o cabeçalho para download do arquivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="exportacao_dados.xlsx"');
header('Cache-Control: max-age=0');

// Cria e salva o arquivo Excel na saída
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Finaliza o script
exit();
