import {config} from "../config.js";
import {actions} from "./actions.js";

async function getCategorias()
{
    let request = new URL(actions.PATH ,config.URL_API);
    let data = await fetch(request);
    return await data.json();
}

export {getCategorias}

function loadCategorias()
{
    getCategorias()
        .then(result => {
           let lista = document.getElementById('categorias');
           let categorias = result.data;
           let html = '<ul>';

           categorias.forEach( (categoria) => {
               html += `<li>${categoria['descripcion']}</li>`
            });
            html += "</ul>";
            lista.innerHTML = html;
            })
        .catch( error => {
            console.log('Error al obtener las categorias')
        });
}

function init()
{
    loadCategorias();
}

window["onload"] = init;