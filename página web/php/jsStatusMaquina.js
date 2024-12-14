// ---- atualizar data nos campos Data Início e Data Fim

// atualizar data atual nos campos
document.getElementById("statusMaquinaDataInicio").value = getDataUmAnoAntes();

// atualizar data um ano antes
document.getElementById("statusMaquinaDataFim").value = getDataAtual();

// ---- Exibir dados no gráfico 

// grafico
const cty = document.getElementById('graficoStatusMaquina').getContext('2d');

let graficoStatusMaquina = new Chart(cty, {
type: 'line',
    data: {
        labels: [], // Você vai colocar os valores reais aqui
        datasets: [{
            label: 'Status Máquina',
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: false,
            stepped: true
        }]
    },
    options: {
        scales: {
            x: {
                type: 'linear', // Usa escala linear (para números reais no eixo X)
                position: 'bottom', // Coloca o eixo X na parte inferior
                title: {
                    display: true,
                    text: '' // Título do eixo X
                },
                ticks: {
                    display: false,
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Máquina Ligada/Desligada' // Título do eixo X
                },
            }
        }
    }
});

async function atualizarGraficoStatusMaquina() {

    // obtem a data que foi escolhida em epoch time
    const dataInicioEpoch = transformarDataEmEpoch(document.getElementById("statusMaquinaDataInicio").value, "00:00");
    const dataFimEpoch = transformarDataEmEpoch(document.getElementById("statusMaquinaDataFim").value, "23:59");

    try {
        // pega status da máquina com base nas datas selecionadas
        const resposta = await fetch(`phpGetStatusMaquina.php?dataInicioEpochTime=${dataInicioEpoch}&dataFimEpochTime=${dataFimEpoch}`);
        const dados = await resposta.json();        

        // declara o eixo x e y do gráfico
        graficoStatusMaquina.data.labels = dados[2];
        graficoStatusMaquina.data.datasets[0].data = dados[0];

        // sinaliza a data de início e fim da monitorização dos dados da máquina
        const inicioMonitoramento = dados[1][0];
        const fimMonitoramento = dados[1][Object.keys(dados[1]).length - 1];
        graficoStatusMaquina.options.scales.x.title.text = 
            `Status da máquina entre os dias ${inicioMonitoramento} e ${fimMonitoramento}
        `;

        // atualiza gráfico
        graficoStatusMaquina.update();

        // atualiza status da máquina (ligado/desligado)
        // declara a quantidade atual de tijolos aprovados
        document.getElementById("statusMaquina").innerHTML = dados[3] == '1' ? 'Ligada' : 'Desligada';

    } catch (error) {
        console.error("Erro ao atualizar o gráfico:", error);
    }
    
}

setInterval(atualizarGraficoStatusMaquina, 10000); // Atualiza a cada 10 segundos
atualizarGraficoStatusMaquina(); // Atualiza imediatamente na primeira carga

// ----- Download dos dados exibidos no gráfico

// pegar dados do peso dos tijolos e fazer um download em excel
function downloadStatusMaquina() {
    // obtem a data que foi escolhida em epoch time
    const dataInicioEpoch = transformarDataEmEpoch(document.getElementById("statusMaquinaDataInicio").value, "00:00");
    const dataFimEpoch = transformarDataEmEpoch(document.getElementById("statusMaquinaDataFim").value, "23:59");

    fetch('phpSalvarExcelStatusMaquina.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `dataInicioEpochTime=${dataInicioEpoch}&dataFimEpochTime=${dataFimEpoch}`
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'status_maquina.xlsx';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => console.error("Erro ao baixar o arquivo:", error));
}




