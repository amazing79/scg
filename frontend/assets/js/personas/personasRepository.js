import {actions} from "./actions.js";
import {config} from "../config.js";
import {SessionManager} from "../common/sessionManager.js";

let persona = {
    idPersona:0,
    apellido:'',
    nombre:'',
    apodo:''
}

let uri = `${SessionManager.getAuthToken()}/${actions.PATH}`;
async function getPersons()
{
    let request = new URL(uri ,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener las personas');
}

async function showPerson(id)
{
    let request = new URL(uri + `/${id}`,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener la persona');
}

async function storePerson(person)
{
    let request = new URL(uri,config.URL_API);
    let options = {};
    options = {
        method: actions.CREATE, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(person),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar registrar a la persona');
}

async function updatePerson(person)
{
    let request = new URL(uri + `/${person.idPersona}` ,config.URL_API);
    let options = {};
    options = {
        method: actions.EDIT, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(person),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar actualizar la persona');
}

async function deletePerson(id)
{
    let request = new URL(uri + `/${id}` ,config.URL_API);
    let options = {};
    options = {
        method: actions.DELETE, // *GET, POST, PUT, DELETE, etc.
        //body:{},
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar borrar la persona');
}

export {persona, getPersons, showPerson, storePerson, updatePerson, deletePerson}