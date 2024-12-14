<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tombador de Tijolo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- link para bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- link para chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- link para ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- css externo para estilizar o calendário -->
  <link rel="stylesheet" type="text/css" href="cssCalendario.css" media="screen" />

</head>
<body>

<!------------------------- Titulo Site ------------------------------>

<div class="container-fluid p-5 bg-success text-white text-center">
  <h1>Tombador de Tijolo</h1> 
</div>

<!------------------------- Status da Máquina ------------------------------>

<div class="container p-2  mt-5 bg-light text-dark text-center">
  <h1>Status da máquina</h1> 
</div>

<!-- exibe o status atual da máquina -->
<div class="container mt-3">
  <div class="input-group me-3">
    <span class="input-group-text bg-success text-white">Status da máquina</span>
    <span class="input-group-text bg-light text-dark" id="statusMaquina"></span>
  </div>
</div>

<div class="container  mt-1">
  <div class="d-flex justify-content-end">

    <!-- inputs de data -->
    <div class="container d-flex gap-2">  

      <div class="date-container">
        <label for="data">Início:</label>
        <input type="date" id="statusMaquinaDataInicio">
      </div>
      
      <div class="date-container">
        <label for="data">Fim:</label>
        <input type="date" id="statusMaquinaDataFim">
      </div>

    </div>

    <!-- botões de atualizar e download -->
    <div class="d-flex gap-2">
      <button class="btn btn-success" onclick="atualizarGraficoStatusMaquina()">
        <i class="fas fa-rotate-right"></i> Atualizar
      </button>

      <button class="btn btn-success" onclick="downloadStatusMaquina()">
        <i class="fas fa-download"></i> Download
      </button>
    </div>

  </div>
</div>

<!-- Gráfico de Peso -->
<div class="container mt-2">
  <canvas id="graficoStatusMaquina"></canvas>
</div>



<!------------------------- Classificação Tijolos ------------------------------>

<div class="container p-2  mt-5 bg-light text-dark text-center">
  <h1>Classificação Tijolos</h1> 
</div>

<!-- exibe o número atual de tijolos aprovados -->
<div class="container mt-3">
  <div class="input-group me-3">
    <span class="input-group-text bg-success text-white">Tijolos Aprovados</span>
    <span class="input-group-text bg-light text-dark" id="tijolosAprovados"></span>
  </div>
</div>


<div class="container  mt-1">
  <div class="d-flex justify-content-end">

    <!-- inputs de data -->
    <div class="container d-flex gap-2">  

      <div class="date-container">
        <label for="data">Início:</label>
        <input type="date" id="classificacaoTijoloDataInicio">
      </div>
      
      <div class="date-container">
        <label for="data">Fim:</label>
        <input type="date" id="classificacaoTijoloDataFim">
      </div>

    </div>

    <!-- botões de atualizar e download -->
    <div class="d-flex gap-2">
      <button class="btn btn-success" onclick="atualizarGraficoClassificacaoTijolos()">
        <i class="fas fa-rotate-right"></i> Atualizar
      </button>

      <button class="btn btn-success" onclick="downloadClassificacaoTijolos()">
        <i class="fas fa-download"></i> Download
      </button>
    </div>

  </div>
</div>

<!-- Gráfico de classificacao tijolo -->
<div class="container mt-2">
  <canvas id="graficoClassificacaoTijolo"></canvas>
</div>



<!------------------------- Peso dos Tijolos ------------------------------>

<div class="container p-2  mt-5 bg-light text-dark text-center">
  <h1>Peso dos Tijolos</h1> 
</div>

<div class="container mt-5">

  <!-- insere o número de tijolos -->
  <div class="input-group mb-3">
    <span class="input-group-text bg-success text-white">Número de tijolos</span>
    <input type="number" class="form-control" id="quantidade1Tijolo" value="100" style="max-width: 100px;">
  </div>

  <!-- calcula a média de peso e apresenta botões de atualizar gráfico e download -->
  <div class="d-flex justify-content-end">
    
    <div class="input-group me-3">
      <span class="input-group-text bg-success text-white">Média do peso<br>dos tijolos</span>
      <span class="input-group-text bg-light text-dark" id="mediaPesoTijolo"></span>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-success" onclick="atualizarGraficoEMediaPesoTijolo()">
        <i class="fas fa-rotate-right"></i> Atualizar
      </button>

      <button class="btn btn-success" onclick="downloadPesoTijolo()">
        <i class="fas fa-download"></i> Download
      </button>
    </div>
    
  </div>
</div>

<!-- Gráfico de Peso -->
<div class="container mt-2">
  <canvas id="graficoPeso"></canvas>
</div>


<br>
<br>
<br>
<br>

</body>
</html>

<script src="jsFuncoesComuns.js"></script>
<script src="jsStatusMaquina.js"></script>
<script src="jsClassificacaoTijolos.js"></script>
<script src="jsPesoTijolos.js"></script>



