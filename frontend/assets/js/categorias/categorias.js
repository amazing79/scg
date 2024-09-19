import {config} from "../config.js";
import {actions} from "./actions.js";

const closeButton = document.getElementById("cancelBtn");
const confirmBtn = document.getElementById("confirmBtn");

async function getCategories()
{
    let request = new URL(actions.PATH ,config.URL_API);
    let data = await fetch(request);
    return await data.json();
}

function loadCategories()
{
    getCategories()
        .then(result => {
           let lvw = document.getElementById('lvw-data');
           let categorias = result.data;

           categorias.forEach((categoria) => {
               let row = loadDataRow(categoria, 'idCategoria');
               lvw.appendChild(row);
           });
        })
        .catch( error => {
            console.log('Error al obtener las categorias')
        });
}

closeButton.addEventListener("click", () => {
    let page = document.getElementById('body');
    const dialog = document.getElementById("dialog_confirm");
    page.classList.remove('bg__blur');
    dialog.close();
});
confirmBtn.addEventListener("click", (event) => {
    event.preventDefault();
    let page = document.getElementById('body');
    const dialog = document.getElementById("dialog_confirm");
    evt_delete(localStorage.getItem('idRow'));
    page.classList.remove('bg__blur');
    dialog.close();
    localStorage.clear();
});

function init()
{
    loadCategories();
}

window["onload"] = init;