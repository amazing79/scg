let config = {
    id:'',
    key:'',
    name:'',
    fields: [],
    showFields: null
}

class Lvw {
    constructor(config, handleEdit = null, handleDelete = null, formDialog = null)
    {
        this._id = config.id;
        this._key = config.key;
        this._name = config.name;
        this._fields = config.fields
        this._visible_fields = config.showFields;
        this._handle_edit = handleEdit ?? function (){ console.log('handler edit no definido');}
        this._handle_delete = handleDelete ?? function (){ console.log('handler delete no definido');};
        this._formDialog = document.getElementById(formDialog);
    }

    makeCaption()
    {
        let _caption = document.createElement('caption');
        _caption.innerText = this._name;
        return _caption;
    }

    makeHeader()
    {
        let _tr = document.createElement('tr');

        this._fields.forEach(field => {
            let _th = document.createElement('th');
            _th.setAttribute('scope', 'col');
            _th.innerText = field;
            _tr.appendChild(_th);
        });
        //agrego columna para btns
        let _th = document.createElement('th');
        _th.className = 'title_actions'
        _th.innerText = 'actions';
        _tr.appendChild(_th);
        //finalmente creo el encabezado
        let _thead = document.createElement('thead');
        _thead.appendChild(_tr);
        return _thead;
    }

    makeBody(data)
    {
        let _tbody = document.createElement('tbody');
        _tbody.setAttribute('id', `${this._id}_body`);
        data.forEach(item => {
            _tbody.appendChild(this.insertItemRow(item));
        })
        return _tbody;
    }

    insertItemRow(obj)
    {
        let _tr = document.createElement('tr');
        _tr.setAttribute('id', `lvw_row_${obj[this._key]}`);

        let columns = this._visible_fields ?? obj;

        for(let prop in columns) {
            let _td = document.createElement('td');
            _td.innerText = obj[prop];
            _td.setAttribute('data-column', prop);
            _tr.appendChild(_td);
        }
        //agrego botons para interactuar con el elemento
        let btns = this.makeItemBtns(obj[this._key]);
        _tr.appendChild(btns)
        return _tr;
    }

    makeItemBtns(id = 0)
    {
        let _td = document.createElement('td');
        let _btn_container = document.createElement('div');
        let _btn_edit = this.createEditButton(id);
        let _btn_delete = this.createDeleteButton(id);
        _btn_container.className = 'col_actions'
        _btn_container.appendChild(_btn_edit)
        _btn_container.appendChild(_btn_delete)
        _td.appendChild(_btn_container);
        return _td;
    }

    makeMainBtn()
    {
        let _div = document.createElement('div');
        let _addBtn = document.createElement('btn');
        _addBtn.className = 'btn primary';
        _addBtn.setAttribute('id', 'lvw_addBtn');
        _addBtn.addEventListener('click', e => this.showFormDialog(e));
        _addBtn.innerText = 'Agregar';
        _div.appendChild(_addBtn)
        return _div;
    }

    buildLvw(data)
    {
        let container = document.getElementById(this._id);
        let tableContainer = document.createElement('div');
        let table = document.createElement('table');
        table.appendChild(this.makeCaption());
        table.appendChild(this.makeHeader());
        table.appendChild(this.makeBody(data));
        tableContainer.setAttribute("id", "lvw-table-container");
        tableContainer.appendChild(table);
        container.appendChild(tableContainer);
        container.appendChild(this.makeMainBtn())
    }

    createEditButton(id) {
        let _btn = document.createElement('btn');
        _btn.className = 'edit icon';
        _btn.setAttribute('id', `edit_${id}`);
        _btn.setAttribute('data-id', id);
        _btn.innerHTML = '<i class="fas fa-edit"></i>';
        _btn.addEventListener('click', e => this._handle_edit(e));
        return _btn;
    }

    createDeleteButton(id) {
        let _btn = document.createElement('btn');
        _btn.className = 'delete icon';
        _btn.setAttribute('id', `delete_${id}`);
        _btn.setAttribute('data-id', id);
        _btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
        _btn.addEventListener('click', e => this._handle_delete(e));
        return _btn;
    }

    showFormDialog()
    {
        let page = document.getElementById('content');
        page.classList.add('bg__blur');
        this._formDialog.showModal();
    }

    addRow(obj)
    {
        let lvw = document.getElementById(`${this._id}_body`);
        let row = this.insertItemRow(obj);
        lvw.appendChild(row);
    }

    deleteRow(id)
    {
        let row = document.getElementById(`lvw_row_${id}`);
        if(row) {
            row.remove();
        } else {
            console.log(`No se encontro la fila ${id} que quiere eliminar`);
        }
    }

    updateRow(obj)
    {
        let tr  = document.getElementById(`lvw_row_${obj[this._key]}`);
        for(let i=0; i< tr.children.length; i++){
            let child = tr.children[i];
            if (child.dataset.column !== undefined && child.dataset.column !== this._key){
                child.innerHTML = obj[child.dataset.column];
            }
        }
    }
}

export {config, Lvw}