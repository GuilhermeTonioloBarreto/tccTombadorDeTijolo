// ---- atualizar data nos campos Data Início e Data Fim

// atualizar data atual nos campos
document.getElementById("classificacaoTijoloDataInicio").value = getDataUmAnoAntes();

// atualizar data um ano antes
document.getElementById("classificacaoTijoloDataFim").value = getDataAtual();


// ---- exibir dados no gráfico

const ctz = document.getElementById('graficoClassificacaoTijolo').getContext('2d');
let graficoClassificacaoTijolo =  new Chart(ctz, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Classificação Tijolos',
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            x: { 
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantidade Tijolos' // Título do eixo X
                }, 
            },
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Tijolos Aprovados/Reprovados' // Título do eixo X
                },
            }
        }
    }
});

async function atualizarGraficoClassificacaoTijolos() {
    // obtem a data que foi escolhida em epoch time
    const dataInicioEpoch = transformarDataEmEpoch(document.getElementById("classificacaoTijoloDataInicio").value, "00:00");
    const dataFimEpoch = transformarDataEmEpoch(document.getElementById("classificacaoTijoloDataFim").value, "23:59");

    //console.log(dataInicioEpoch);
    //console.log(dataFimEpoch);

    try {
        // pega status da máquina com base nas datas selecionadas
        const resposta = await fetch(`phpGetClassificacaoTijolo.php?dataInicioEpochTime=${dataInicioEpoch}&dataFimEpochTime=${dataFimEpoch}`);
        const dados = await resposta.json();   
        
        // declara o eixo x e y do gráfico
        graficoClassificacaoTijolo.data.labels = dados[0];
        graficoClassificacaoTijolo.data.datasets[0].data = dados[1];
        graficoClassificacaoTijolo.update();

        // declara a quantidade atual de tijolos aprovados
        document.getElementById("tijolosAprovados").innerHTML = dados[2];

    } catch (error) {
        console.error("Erro ao atualizar o gráfico:", error);
    }
}

setInterval(atualizarGraficoClassificacaoTijolos, 10000); // Atualiza a cada 10 segundos
atualizarGraficoClassificacaoTijolos(); // Atualiza imediatamente na primeira carga

// ----- Download dos dados exibidos no gráfico


// pegar dados do peso dos tijolos e fazer um download em excel
function downloadClassificacaoTijolos() {
    // obtem a data que foi escolhida em epoch time
    const dataInicioEpoch = transformarDataEmEpoch(document.getElementById("classificacaoTijoloDataInicio").value, "00:00");
    const dataFimEpoch = transformarDataEmEpoch(document.getElementById("classificacaoTijoloDataFim").value, "23:59");

    fetch('phpSalvarExcelClassificacaoTijolo.php?dataInicioEpochTime=${dataInicioEpoch}&dataFimEpochTime=${dataFimEpoch}', {
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
        a.download = 'classificacao_tijolo.xlsx';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => console.error("Erro ao baixar o arquivo:", error));
}

