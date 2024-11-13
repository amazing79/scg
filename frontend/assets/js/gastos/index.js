import {showReportByCategoria} from './gastosRepository.js';

function getLabels(result) {
    let tempArr = [];
    result.forEach(obj => {
        if (!tempArr.includes(obj.descripcion)) {
            tempArr.push(obj.descripcion)
        }
    })
    return tempArr.sort();
}

function getDataSets(result, lblArr) {
    let dataSetsTemplate = {label:'', data:''}
    let first = true;
    let actual = 0;
    let set = {}
    let ds = [];
    let initial_values = [];
    let idxArr = [];

    for(let idx=0; idx < lblArr.length; idx++)
    {
        initial_values.push(0);
        idxArr[lblArr[idx]] = idx;
    }

    result.forEach(obj => {
        if (first) {
            first = false;
            set = Object.assign({}, dataSetsTemplate);
            set.data = [...initial_values];
            actual = obj.idPersona;
        }
        if (actual === obj.idPersona) {
            set.label = obj.persona;
            if(idxArr.hasOwnProperty(obj.descripcion)) {
                set.data[idxArr[obj.descripcion]] = obj.total;
            }
        } else {
            ds.push(set);
            actual = obj.idPersona;
            set = Object.assign({}, dataSetsTemplate);
            set.data = [];
            set.label = obj.persona;
            if(idxArr.hasOwnProperty(obj.descripcion)) {
                set.data[idxArr[obj.descripcion]] = obj.total;
            }
        }
    })
    ds.push(set);
    return ds;
}

function processData(result) {
    let obj = {labels:[], dataSet:[]};
    let lblArr = getLabels(result);
    let ds = getDataSets(result, lblArr);

    obj.labels = lblArr;
    obj.dataSet = ds;
    return obj;
}

function showChartWithData() {
    showReportByCategoria()
        .then( result => {
            let data = processData(result.data);
            drawChart(data);
        })
        .catch(error => {
            console.log(error)
        });
}
function drawChart(data){
    const ctx = document.getElementById('myChart');
    let periodo = 'noviembre';
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: data.dataSet
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: `Gastos por categorias mes de ${periodo}`
                },
            },
            responsive: true,
            scales: {
                x:{
                    stacked: true
                },
                y: {
                    beginAtZero: true,
                    stacked:true
                }
            }
        }
    });
}

window.addEventListener('load', evt => showChartWithData());
