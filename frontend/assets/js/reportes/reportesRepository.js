import {config} from "../config.js";

let actions  = {
    path: 'reportes',
    gastos_categoria: '/gastos-categoria',
    total_gastos_persona : '/total-gastos-persona'
};

async function showReportByCategoria()
{
    let request = new URL(actions.path + actions.gastos_categoria,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener los gastos por categoria persona');
}
async function showTotalsBillsByPerson()
{
    let request = new URL(actions.path + actions.total_gastos_persona ,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener el total de gastos realizados por persona');
}

export {showReportByCategoria,  showTotalsBillsByPerson}