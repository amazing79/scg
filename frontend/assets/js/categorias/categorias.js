import {Lvw} from "../common/lvw.js";
import {getCategories, showCategory, storeCategory, updateCategory, deleteCategory} from "./categoriasRepository.js"

const formCloseButton = document.getElementById("formCancelBtn");
const dialogConfirmDeleteBtn = document.getElementById("dialogConfirmDeleteBtn");
const dialogConfirmCancelBtn = document.getElementById("dialogConfirmCancelBtn");

let lvw = new Lvw(
    'lvw-data',
    'idCategoria',
    'Categorias',
    ['id', 'descripcion'],
    editCategory,
    showConfirmDialog,
    'itemDialog'
);

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
    let obj = {idCategoria: 0, descripcion: ''};
    let id = document.getElementById('itemId');
    let descripcion = document.getElementById('descripcion');
    obj.idCategoria = id.value ?? 0;
    obj.descripcion = descripcion.value.trim() ?? '';
    return {obj, id, descripcion};
}

function evtUpdateCategory()
{
    let {obj, id, descripcion} = loadObjectData();
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
    let {obj, id, descripcion} = loadObjectData();
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
    getCategories()
        .then(result => {
           let categorias = result.data;
           lvw.buildLvw(categorias);
        })
        .catch( error => {
            console.log(error.message)
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
function init()
{
    loadCategories();
}

window["onload"] = init;