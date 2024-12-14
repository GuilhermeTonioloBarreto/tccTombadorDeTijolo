// atualizar a data de inicio e fim do dia
function getDataAtual(){
    const dataAtual = new Date();
    const ano = dataAtual.getFullYear();
    const mes = String(dataAtual.getMonth() + 1).padStart(2, '0'); // Mês começa em 0, por isso somamos 1
    const dia = String(dataAtual.getDate()).padStart(2, '0');
    const dataFormatada = `${ano}-${mes}-${dia}`;

    return dataFormatada;
}

// atualizar a data de inicio e fim do dia
function getDataUmAnoAntes(){
    const dataAtual = new Date();
    const ano = dataAtual.getFullYear() - 1;
    const mes = String(dataAtual.getMonth() + 1).padStart(2, '0'); // Mês começa em 0, por isso somamos 1
    const dia = String(dataAtual.getDate()).padStart(2, '0');
    const dataFormatada = `${ano}-${mes}-${dia}`;

    return dataFormatada;
}

function transformarDataEmEpoch(dataString, horaString){
    const dataHoraString = `${dataString}T${horaString}:00`;
    const dataDate = new Date(dataHoraString);
    epochTime = Math.floor(dataDate.getTime() / 1000);
    return epochTime;
}