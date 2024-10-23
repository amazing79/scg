import {config, Lvw} from "../common/lvw.js";
import {Loader} from "../common/loader.js";
import {getCategories, showCategory, storeCategory, updateCategory, deleteCategory, categoria} from "./categoriasRepository.js"

config.id = 'lvw-data';
config.key = 'idCategoria';
config.name = 'Categorias';
config.fields = ['id', 'descripcion'];
let lvw = new Lvw(
    config,
    editCategory,
    showConfirmDialog,
    'itemDialog'
);

let loader = new Loader('lvw-data','loading');

function showConfirmDialog(evt)
{
    const dialog = document.getElementById("confirmDialog");
    let page = document.getElementById('content');
    let btn = evt.currentTarget;
    localStorage.setItem('idRow', btn.dataset.id);
    page.classList.add('bg__blur');
    dialog.showModal();
}

function editCategory(evt)
{
    let btn = evt.currentTarget;
    let idCategoria = btn.dataset.id;
    showCategory(idCategoria)
        .then(response => {
           let descripcion = document.getElementById('descripcion');
           let hdnId = document.getElementById('itemId');
           descripcion.value = response.data.descripcion ?? '';
           hdnId.value = response.data.idCategoria ?? 0;
           lvw.showFormDialog();
        })
        .catch(error => {
            console.log(error.message)
        })
}

function loadObjectData() {
    let obj = Object.assign({}, categoria);
    let id = document.getElementById('itemId');
    let descripcion = document.getElementById('descripcion');
    obj.idCategoria = id.value ?? 0;
    obj.descripcion = descripcion.value.trim() ?? '';
    return obj;
}

function evtUpdateCategory()
{
    let obj = loadObjectData();
    updateCategory(obj)
        .then(response => {
            console.log(response);
            lvw.updateRow(obj);
        })
        .catch(error => {
            console.log(error.message)
        })
        .finally( _ => {
            closeForm();
        });

}

function evtCreateCategory()
{
    let obj = loadObjectData();
    storeCategory(obj)
        .then(response => {
            console.log(response);
            obj.idCategoria = response.data;
            lvw.addRow(obj)
        })
        .catch(error => {
            console.log(error.message)
        })
        .finally( _ => {
            closeForm();
        });
}
function loadCategories()
{
    loader.showLoading();
    getCategories()
        .then(result => {
            loader.removeLoading();
            let categorias = result.data;
            lvw.buildLvw(categorias);
        })
        .catch( error => {
            console.log(error.message)
        });
}

function initEvents()
{
    const formCloseButton = document.getElementById("formCancelBtn");
    const dialogConfirmDeleteBtn = document.getElementById("dialogConfirmDeleteBtn");
    const dialogConfirmCancelBtn = document.getElementById("dialogConfirmCancelBtn");
    formCloseButton.addEventListener("click", () => {
        let page = document.getElementById('content');
        const dialog = document.getElementById("itemDialog");
        page.classList.remove('bg__blur');
        dialog.close();
    });

    dialogConfirmDeleteBtn.addEventListener("click", (event) => {
        let page = document.getElementById('content');
        const dialog = document.getElementById("confirmDialog");
        let idCategory = localStorage.getItem('idRow');
        deleteCategory(idCategory)
            .then(response => {
                console.log(response)
                lvw.deleteRow(idCategory);
            })
            .catch( error => {
                console.log(error.message)
            });
        page.classList.remove('bg__blur');
        dialog.close();
        localStorage.clear();
    });

    dialogConfirmCancelBtn.addEventListener("click", (event) => {
        let page = document.getElementById('content');
        const dialog = document.getElementById("confirmDialog");
        page.classList.remove('bg__blur');
        dialog.close();
        localStorage.clear();
    });

    document.getElementById('itemForm').addEventListener('submit', (event) => {
        event.preventDefault();
        let idCategoria =  document.getElementById('itemId').value;
        if(Number.parseInt(idCategoria,10) !== 0 ) {
            evtUpdateCategory();
        } else {
            evtCreateCategory();
        }
    });

    document.getElementById('itemDialog').addEventListener('close', evt => {
        closeForm();
        document.getElementById('content').classList.remove('bg__blur');
    });

    document.getElementById('confirmDialog').addEventListener('close', evt => {
        document.getElementById('content').classList.remove('bg__blur');
    });
}

function closeForm()
{
    let id = document.getElementById('itemId');
    let description = document.getElementById('descripcion');
    const dialog = document.getElementById("itemDialog");
    id.value = 0;
    description.value = '';
    dialog.close();
}



function init()
{
    loadCategories();
    initEvents();
}
document.addEventListener('DOMContentLoaded', function () {
    init();
});