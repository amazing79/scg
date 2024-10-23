import {Lvw} from "../common/lvw.js";
import {Loader} from "../common/loader.js";
import {getPersons} from "../personas/personasRepository.js"
import {getCategories} from "../categorias/categoriasRepository.js";
import {gasto, getBills, showBill, storeBill, updateBill, deleteBill} from "./gastosRepository.js";

let visibleData = {
    idGasto: 0,
    fecha:0,
    descripcion: '',
    monto: 0,
    categoria: 0,
    datosPersona:'',
    observaciones:''
}

let lvw = new Lvw(
    'lvw-data',
    'idGasto',
    'Gastos',
    ['ID', 'Fecha Gasto', 'Descripcion', 'Monto', 'Categoria', 'Persona', 'Observaciones'],
    editBill,
    showConfirmDialog,
    'itemDialog',
    visibleData
);

let loader = new Loader('lvw-data','loading');
let categoriesIndex = [];
let personsIndex = [];

function showConfirmDialog(evt)
{
    const dialog = document.getElementById("confirmDialog");
    let page = document.getElementById('content');
    let btn = evt.currentTarget;
    localStorage.setItem('idRow', btn.dataset.id);
    page.classList.add('bg__blur');
    dialog.showModal();
}

function editBill(evt)
{
    let btn = evt.currentTarget;
    let idGasto = btn.dataset.id;
    showBill(idGasto)
        .then(response => {
            let fecha = document.getElementById('fecha');
            let descripcion = document.getElementById('descripcion');
            let monto = document.getElementById('monto');
            let observaciones = document.getElementById('observaciones');
            let categorias = document.getElementById('categoria');
            let persona = document.getElementById('persona');
            let hdnId = document.getElementById('itemId');
            fecha.value = response.data.fecha_gasto ?? '';
            descripcion.value = response.data.descripcion ?? '';
            monto.value = response.data.monto ?? '';
            categorias.selectedIndex = categoriesIndex[response.data.categoria ?? 0];
            persona.selectedIndex = personsIndex[response.data.persona ?? 0];
            observaciones.value = response.data.observaciones ?? '';
            hdnId.value = response.data.idGasto ?? 0;
            lvw.showFormDialog();
        })
        .catch(error => {
            console.log(error.message)
        })
}

function loadObjectData() {
    let obj = Object.assign({}, gasto);
    let id = document.getElementById('itemId');
    let fecha = document.getElementById('fecha');
    let descripcion = document.getElementById('descripcion');
    let monto = document.getElementById('monto');
    let categoria = document.getElementById('categoria');
    let persona = document.getElementById('persona');
    let observaciones = document.getElementById('observaciones');
    obj.idGasto = id.value ?? 0;
    obj.fecha = fecha.value.trim() ?? '';
    obj.descripcion = descripcion.value.trim() ?? '';
    obj.monto = monto.value.trim() ?? 0.0;
    obj.categoria = categoria[categoria.selectedIndex].value ?? 0;
    obj.persona = persona[persona.selectedIndex].value ?? 0;
    obj.observaciones = observaciones.value.trim() ?? '';
    return obj;
}

function evtUpdateBill()
{
    let obj = loadObjectData();
    updateBill(obj)
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

function evtCreateBill()
{
    let obj = loadObjectData();
    storeBill(obj)
        .then(response => {
            console.log(response);
            obj.idGasto = response.data;
            lvw.addRow(obj)
        })
        .catch(error => {
            console.log(error.message)
        })
        .finally( _ => {
            closeForm();
        });
}
function loadBills()
{
    loader.showLoading();
    getBills()
        .then(result => {
            loader.removeLoading();
            let bills = result.data;
            lvw.buildLvw(bills);
        })
        .catch( error => {
            console.log(error.message)
        });
}

function loadCategories()
{
    getCategories()
        .then(result => {
            let categories = result.data;
            let categoriesContainer = document.getElementById('categoria');
            let htmlResult = '';
            let idx = 0;
            htmlResult += `<option value="0">--Seleccione--</option>`;
            categoriesIndex[0] = 0;

            categories.forEach( category => {
                idx++;
                htmlResult += `<option value="${category.idCategoria}">${category.descripcion}</option>`;
                categoriesIndex[category.idCategoria] = idx;
            })
            categoriesContainer.innerHTML = htmlResult;
        })
        .catch( error => {
            console.log(error.message)
        });
}

function loadPersons()
{
    getPersons()
        .then(result => {
            let persons = result.data;
            let personasContainer = document.getElementById('persona');
            let htmlResult = '';
            let idx = 0;
            htmlResult += `<option value="0">--Seleccione--</option>`;
            persons.forEach( person => {
                idx++;
                htmlResult += `<option value="${person.idPersona}">${person.apellido}, ${person.nombre}</option>`;
                personsIndex[person.idPersona] = idx;
            })
            personasContainer.innerHTML = htmlResult;
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
        let idGasto = localStorage.getItem('idRow');
        deleteBill(idGasto)
            .then(response => {
                console.log(response)
                lvw.deleteRow(idGasto);
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
        let idGasto =  document.getElementById('itemId').value;
        if(Number.parseInt(idGasto,10) !== 0 ) {
            evtUpdateBill();
        } else {
            evtCreateBill();
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
    let fecha = document.getElementById('fecha');
    let descripcion = document.getElementById('descripcion');
    let monto = document.getElementById('monto');
    let categoria = document.getElementById('categoria');
    let persona = document.getElementById('persona');
    let observaciones = document.getElementById('observaciones');
    const dialog = document.getElementById("itemDialog");
    id.value = 0;
    fecha.value = '';
    descripcion.value = '';
    monto.value = 0.0;
    categoria.selectedIndex = 0;
    persona.selectedIndex = 0;
    observaciones.value = '';
    dialog.close();
}

function init()
{
    loadBills();
    loadCategories();
    loadPersons();
    initEvents();
}
document.addEventListener('DOMContentLoaded', function () {
    init();
});