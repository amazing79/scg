import {Lvw} from "../common/lvw.js";
import {Loader} from "../common/loader.js";
import {persona, getPersons, showPerson, storePerson, updatePerson, deletePerson} from "./personasRepository.js"

let lvw = new Lvw(
    'lvw-data',
    'idPersona',
    'Personas',
    ['id', 'apellido', 'nombre', 'apodo'],
    editPerson,
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

function editPerson(evt)
{
    let btn = evt.currentTarget;
    let idPersona = btn.dataset.id;
    showPerson(idPersona)
        .then(response => {
            let apellido = document.getElementById('apellido');
            let nombre = document.getElementById('nombre');
            let apodo = document.getElementById('apodo');
            let hdnId = document.getElementById('itemId');
            apellido.value = response.data.apellido ?? '';
            nombre.value = response.data.nombre ?? '';
            apodo.value = response.data.apodo ?? '';
            hdnId.value = response.data.idPersona ?? 0;
            lvw.showFormDialog();
        })
        .catch(error => {
            console.log(error.message)
        })
}

function loadObjectData() {
    let obj = Object.assign({}, persona);
    let id = document.getElementById('itemId');
    let apellido = document.getElementById('apellido');
    let nombre = document.getElementById('nombre');
    let apodo = document.getElementById('apodo');
    obj.idPersona = id.value ?? 0;
    obj.apellido = apellido.value.trim() ?? '';
    obj.nombre = nombre.value.trim() ?? '';
    obj.apodo = apodo.value.trim() ?? '';
    return obj;
}

function evtUpdatePerson()
{
    let obj = loadObjectData();
    updatePerson(obj)
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

function evtCreatePerson()
{
    let obj = loadObjectData();
    storePerson(obj)
        .then(response => {
            console.log(response);
            obj.idPersona = response.data;
            lvw.addRow(obj)
        })
        .catch(error => {
            console.log(error.message)
        })
        .finally( _ => {
            closeForm();
        });
}
function loadPersons()
{
    loader.showLoading();
    getPersons()
        .then(result => {
            loader.removeLoading();
            let personas = result.data;
            lvw.buildLvw(personas);
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
        let idPersona = localStorage.getItem('idRow');
        deletePerson(idPersona)
            .then(response => {
                console.log(response)
                lvw.deleteRow(idPersona);
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
        let idPersona =  document.getElementById('itemId').value;
        if(Number.parseInt(idPersona,10) !== 0 ) {
            evtUpdatePerson();
        } else {
            evtCreatePerson();
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
    let apellido = document.getElementById('apellido');
    let nombre = document.getElementById('nombre');
    let apodo = document.getElementById('apodo');
    const dialog = document.getElementById("itemDialog");
    id.value = 0;
    apellido.value = '';
    nombre.value = '';
    apodo.value = '';
    dialog.close();
}

function init()
{
    loadPersons();
    initEvents();
}
document.addEventListener('DOMContentLoaded', function () {
    init();
});