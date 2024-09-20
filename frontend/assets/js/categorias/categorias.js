import {Lvw} from "../common/lvw.js";
import {getCategories, showCategory, storeCategory, updateCategory, deleteCategory} from "./categoriasRepository.js"

const closeButton = document.getElementById("cancelBtn");
const confirmBtn = document.getElementById("confirmBtn");
const saveBtn = document.getElementById('data-proccess');

let lvw = new Lvw('lvw-data', 'Categorias', ['id', 'descripcion'], editCategory, showConfirmDialog);

function showConfirmDialog(evt)
{
    const dialog = document.getElementById("dialog_confirm");
    let page = document.getElementById('body');
    let btn = evt.target;
    localStorage.setItem('idRow', btn.dataset.id);
    page.classList.add('bg__blur');
    dialog.showModal();
}

function editCategory(evt)
{
    let btn = evt.target;
    let idCategoria = btn.dataset.id;
    showCategory(idCategoria)
        .then(response => {
           let path = window.location.origin
           let descripcion = document.getElementById('descripcion');
           let hdn_id = document.getElementById('hdn_id');
           descripcion.value = response.data.descripcion ?? '';
           hdn_id.value = response.data.idCategoria ?? 0;
           window.location.href = path + '/slim/scg/#show-frm';
        })
        .catch(error => {
            console.log(error.message)
        })
}

function evtUpdateCategory()
{
    let obj = {idCategoria:0, descripcion:''};
    let id = document.getElementById('hdn_id');
    let descripcion = document.getElementById('descripcion');
    obj.idCategoria = id.value ?? 0;
    obj.descripcion = descripcion.value.trim() ?? '';
    updateCategory(obj)
        .then(response => {
            console.log(response);
            lvw.updateRow(obj);
            window.location.href="#";
        })
        .catch(error => {
            console.log(error.message)
        })

}

function evtCreateCategory()
{
    let obj = {idCategoria:0, descripcion:''};
    let id = document.getElementById('hdn_id');
    let descripcion = document.getElementById('descripcion');
    obj.idCategoria = id.value ?? 0;
    obj.descripcion = descripcion.value.trim() ?? '';
    storeCategory(obj)
        .then(response => {
            console.log(response);
            //lvw.addRow(obj)
            window.location.href="#";
        })
        .catch(error => {
            console.log(error.message)
        })

}
function loadCategories()
{
    getCategories()
        .then(result => {
           let categorias = result.data;
           lvw.buildLvw(categorias);
        })
        .catch( error => {
            console.log(error.message)
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
    let idCategory = localStorage.getItem('idRow');
    deleteCategory(idCategory)
        .then(response => {
            lvw.deleteRow(idCategory);
        })
        .catch( error => {
            console.log(error.message)
        });
    page.classList.remove('bg__blur');
    dialog.close();
    localStorage.clear();
});

saveBtn.addEventListener("click", evt => {
    let idCategoria =  document.getElementById('hdn_id').value;
    if(Number.parseInt(idCategoria,10) !== 0 ) {
        evtUpdateCategory();
    } else {
        evtCreateCategory();
    }
})

function init()
{
    loadCategories();
}

window["onload"] = init;