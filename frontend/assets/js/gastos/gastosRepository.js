import {actions} from "./actions.js";
import {config} from "../config.js";
import {SessionManager} from "../common/sessionManager.js";

let gasto = {
    idGasto:0,
    fecha:'',
    descripcion:'',
    monto:0.0,
    categoria:0,
    persona:0,
    observaciones:''
}

let uri = `${SessionManager.getAuthToken()}/${actions.PATH}`;
async function getBills()
{
    let request = new URL(uri ,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    const errorsDetails = await response.json();
    if(response.status === 401) {
            const event = new CustomEvent('sessionExpired', {});
            document.dispatchEvent(event);
    } else {
        throw {
            status: response.status,
            statusText : response.statusText,
            details: errorsDetails
        }
    }
}

async function showBill(id)
{
    let request = new URL(uri + `/${id}`,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener el gasto');
}

async function storeBill(gasto)
{
    let request = new URL(uri,config.URL_API);
    let options = {
        method: actions.CREATE, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(gasto),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar registrar el gasto');
}

async function updateBill(gasto)
{
    let request = new URL(uri + `/${gasto.idGasto}` ,config.URL_API);
    let options = {
        method: actions.EDIT, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(gasto),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar actualizar el gasto');
}

async function deleteBill(id)
{
    let request = new URL(uri + `/${id}` ,config.URL_API);
    let options = {
        method: actions.DELETE, // *GET, POST, PUT, DELETE, etc.
        //body:{},
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar borrar el gasto');
}

async function showBillsByPerson(personId, periodo = null)
{
    let ref = uri + `-persona/${personId}`;
    if(periodo !== null) {
        ref += `/${periodo.anio}/${periodo.periodo}`;
    }
    let request = new URL(ref,config.URL_API);
    let options = {
            method: actions.LIST, // *GET, POST, PUT, DELETE, etc.
        };
    let response = await fetch(request, options);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener los gastos de la persona');
}

export {gasto, getBills, showBill, storeBill, updateBill, deleteBill, showBillsByPerson}