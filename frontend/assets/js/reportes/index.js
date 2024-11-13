import {showReportByCategoria, showTotalsBillsByPerson} from "./reportesRepository.js";
import {months} from "../common/months.js";

let categoriesChart, totalChart = null;
function resizeDraw() {
    if(categoriesChart !== null) {
        categoriesChart.resize();
    }

    if(totalChart !== null) {
        totalChart.resize();
    }
}
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

function processCategoriesData(result) {
    let obj = {labels:[], dataSet:[]};
    let lblArr = getLabels(result);
    let ds = getDataSets(result, lblArr);

    obj.labels = lblArr;
    obj.dataSet = ds;
    return obj;
}

function drawChartBillsByCategories() {
    showReportByCategoria()
        .then( result => {
            let data = processCategoriesData(result.data);
            drawChartCategoriesReport(data);
        })
        .catch(error => {
            console.log(error)
        });
}

function processTotalsData(data) {
    let report = {labels:[], values:[]};

    data.forEach( item => {
        report.labels.push(item.persona);
        report.values.push(item.total);
    })
    return report;
}

function drawChartTotalBills() {
    showTotalsBillsByPerson()
        .then( result => {
            let data = processTotalsData(result.data);
            drawChartTotalByPersonReport(data);
        })
        .catch(error => {
            console.log(error)
        });
}

function getPeriodoActualNombre() {
    let fecha = new Date();
    return months[fecha.getMonth()];
}

function drawChartCategoriesReport(data){
    const ctx = document.getElementById('gastos-categoria');
    let periodo = getPeriodoActualNombre();
    categoriesChart = new Chart(ctx, {
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

function drawChartTotalByPersonReport(data){
    const ctx = document.getElementById('total-gastos-persona');
    let periodo = getPeriodoActualNombre();
    totalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Total de gastos por persona',
                data: data.values
            }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: `Gastos por Persona mes de ${periodo}`
                },
            },
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
}

function drawCharts() {
    drawChartBillsByCategories();
    drawChartTotalBills()
}

window.addEventListener('load', evt => drawCharts());
window.addEventListener('resize', evt => resizeDraw());

