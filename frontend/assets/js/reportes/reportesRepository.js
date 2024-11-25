import {config} from "../config.js";
import {SessionManager} from "../common/sessionManager.js";

let actions  = {
    path: 'reportes',
    gastos_categoria: '/gastos-categoria',
    total_gastos_persona : '/total-gastos-persona'
};

async function showReportByCategoria()
{
    let uri = `${SessionManager.getAuthToken()}/${actions.path}`;
    let request = new URL(uri + actions.gastos_categoria,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener los gastos por categoria persona');
}
async function showTotalsBillsByPerson()
{
    let uri = `${SessionManager.getAuthToken()}/${actions.path}`;
    let request = new URL(uri + actions.total_gastos_persona ,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener el total de gastos realizados por persona');
}

export {showReportByCategoria,  showTotalsBillsByPerson}