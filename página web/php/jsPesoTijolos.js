// ---- pegar dados de peso dos tijolos, plota no gráfico, calcula média de peso e exibe ela
const ctx = document.getElementById('graficoPeso').getContext('2d');
let graficoPeso = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Peso Tijolo',
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
                    text: 'Peso Tijolos (Kg)' // Título do eixo X
                },
            }
        }
    }
});

async function atualizarGraficoEMediaPesoTijolo() {
    const quantidade1Tijolo = document.getElementById('quantidade1Tijolo').value || 100;

    try {
        // pega valores de tijolos no mysql
        const resposta = await fetch(`phpGetPesoTijolo.php?quantidade=${quantidade1Tijolo}`);
        const dados = await resposta.json();

        // declara o eixo x e y do gráfico
        graficoPeso.data.labels = dados[0];
        graficoPeso.data.datasets[0].data = dados[1];
        graficoPeso.update();

        // declara a média dos pesos
        document.getElementById('mediaPesoTijolo').innerHTML = dados[2].toFixed(3);

    } catch (error) {
        console.error("Erro ao atualizar o gráfico:", error);
    }
}

setInterval(atualizarGraficoEMediaPesoTijolo, 10000); // Atualiza a cada 10 segundos
atualizarGraficoEMediaPesoTijolo(); // Atualiza imediatamente na primeira carga

// ---- salvar dados peso tijolo no excel 

// pegar dados do peso dos tijolos e fazer um download em excel
function downloadPesoTijolo() {
    const quantidade1Tijolo = document.getElementById('quantidade1Tijolo').value || 100;

    fetch('phpSalvarExcelPesoTijolo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `quantidade=${quantidade1Tijolo}`
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'peso_tijolo.xlsx';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => console.error("Erro ao baixar o arquivo:", error));
}
