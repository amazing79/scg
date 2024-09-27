import {actions} from "./actions.js";
import {config} from "../config.js";

let categoria = {
    idCategoria:0,
    descripcion:''
}
async function getCategories()
{
    let request = new URL(actions.PATH ,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener las categorias');
}



async function showCategory(id)
{
    let request = new URL(actions.PATH + `/${id}`,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener la categoria');
}

async function storeCategory(category)
{
    let request = new URL(actions.PATH,config.URL_API);
    let options = {};
    options = {
        method: actions.CREATE, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(category),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar actualizar la categoria');
}

async function updateCategory(category)
{
    let request = new URL(actions.PATH + `/${category.idCategoria}` ,config.URL_API);
    let options = {};
    options = {
        method: actions.EDIT, // *GET, POST, PUT, DELETE, etc.
        headers:{
            "Content-Type": "application/json",
        },
        body:JSON.stringify(category),
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar actualizar la categoria');
}

async function deleteCategory(id)
{
    let request = new URL(actions.PATH + `/${id}` ,config.URL_API);
    let options = {};
    options = {
        method: actions.DELETE, // *GET, POST, PUT, DELETE, etc.
        //body:{},
    }
    let response = await fetch(request, options);
    if (response.ok) {
        return await response.json()
    }
    throw new Error('Ocurrio un error al intentar borrar la categoria');
}

export {getCategories, showCategory, storeCategory, updateCategory, deleteCategory, categoria}