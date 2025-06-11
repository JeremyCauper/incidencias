class CTable {
    _contenedor = null;
    _idContenedor = null;
    _selector = null;
    _btnCreate = null;
    _table = null;
    _structure = {
        repit: false,
        table: {},
        extract: [],
        select: {}
    };
    static _accion = true;
    static _newRow = 1;
    static _data = {};

    // Mapa de operaciones
    $operations = {
        '===': (a, b) => a === b,
        '!==': (a, b) => a !== b,
        '>': (a, b) => a > b,
        '<': (a, b) => a < b,
        '>=': (a, b) => a >= b,
        '<=': (a, b) => a <= b,
    }

    constructor(contenedor, options = {}) {
        this._structure = {
            ...this._structure,
            ...options
        };
        this._idContenedor = contenedor;
        this._contenedor = $('#' + contenedor);
        this.dataSet = options.dataSet || [];

        if (!this._contenedor.length) return alert('no se puede seguir el contenedor configurado no existe.');
        if (!this._structure.table?.thead?.length) return alert('no se puede seguir thead no está configurado.');
        if (!this._structure.table?.tbody?.length) return alert('no se puede seguir tbody no está configurado.');

        this._selector = $('<select>', {
            class: 'select-clear',
            'ctable-select': contenedor
        });

        this._btnCreate = $('<button>', {
            type: "button",
            class: 'btn btn-primary px-2 ms-1 rounded',
            'ctable-create': contenedor,
            'data-mdb-ripple-init': ''
        }).append($('<i>', { class: 'fas fa-plus', style: 'pointer-events: none;' }));

        this._contenedor.attr({ class: 'row', 'ctable-content': contenedor }).append(
            $('<div>', { class: 'col-md-9' }).append(
                $('<div>', { class: 'input-group mt-2 mb-2', 'ctable-contentSelect': contenedor }).append(this._selector, this._btnCreate)
            ),
            $('<div>', { class: 'col-12' }).append(
                $('<div>', { 'ctable-contentTable': contenedor })
            )
        );
        this.fillSelect();

        // Agregar el manejador de clics para el botón
        $(`button[ctable-create="${contenedor}"]`).on('click', () => {
            this.createRow();
        });
    }

    get acciones() {
        return CTable._accion;
    }

    set acciones(bol) {
        CTable._accion = bol;
    }

    get newRow() {
        return CTable._newRow;
    }

    set newRow(nw) {
        CTable._newRow = nw;
    }

    get data() {
        return CTable._data;
    }

    set data(dt) {
        CTable._data = dt;
    }

    createRow(value = this._selector.val()) {
        if (!value) return false;
        const obj = (Array.isArray(this.dataSet) ? this.dataSet : Object.values(this.dataSet)).find(v => v[this._structure.table.tbody[0].data] == value);
        if (!obj) return boxAlert.minbox({ i: 'info', h: 'El registro ya existe' });
        this.createTable();

        const extract = this._structure.extract;
        const $id = obj[extract[0]];

        if ($id in this.data) {
            this.data[$id].eliminado = 0;
        } else {
            var obj_td = {};
            extract.forEach(e => {
                obj_td[e] = obj[e];
            });
            obj_td['eliminado'] = 0;
            obj_td['registro'] = this.newRow;
            this.data[$id] = obj_td;
        }
        const $tr = $('<tr>', { 'ctable-table-tr-id': $id, 'ctable-table-tr': this.acciones, 'ctable-table-rnew': this.newRow, 'ctable-repit': obj[this._structure.table.tbody[0].data] });
        this._structure.table.tbody.forEach(col => {
            const $td = $('<td>', { 'ctable-table-col': col.data });
            if (col.render && typeof col.render === 'function') {
                $td.html(col.render(obj[col.data]));
            } else {
                $td.html(obj[col.data]);
            }
            $tr.append($td);
        });
        const actionDelete = $('<td>', { class: 'text-center' }).append($('<i>', { class: "far fa-trash-can text-danger", type: "button", 'ctable-delete': this._idContenedor }));
        const dontDelete = $('<td>', { class: 'text-center' }).append($('<i>', { class: "fas fa-ban text-primary" }));
        $tr.append(this.acciones ? actionDelete : dontDelete);

        this._table.children('tbody').append($tr);

        $(`i[ctable-delete="${this._idContenedor}"]`).off('click').on('click', (event) => {
            this.deleteRow(event.currentTarget);
        });
        this._selector.val('').trigger('change.select2')
            .children(`option[value="${$id}"]`).attr('disabled', true);
        this.acciones = true;
        this.newRow = 1;
    }

    deleteRow($this) {
        const $tr = $($this).parent().parent();
        const $id = $tr.attr('ctable-table-tr-id');
        if (this.data[$id].registro) {
            delete this.data[$id];
        } else {
            this.data[$id].eliminado = 1;
        }
        $tr.remove();
        this._selector.children(`option[value="${$id}"]`).attr('disabled', false);
        if (!this._table.children('tbody').children('tr').length) {
            this.deleteTable();
        }
    }

    createTable() {
        if (this._table === null) {
            this._table = $('<table>', { class: 'table w-100 text-nowrap', 'ctable-table': this._idContenedor }).append(
                $('<thead>').append($('<tr>').html(`<th>${(this._structure.table.thead).join('</th><th>')}</th><th class="text-center">Acciones</th>`)),
                $('<tbody>')
            );
            $(`div[ctable-contentTable="${this._idContenedor}"]`).append(this._table);
        }
    }

    deleteTable() {
        this.data = {};
        if (this._table !== null) this._table.remove();
        this._table = null;
    }

    fillTable(value, Adel = true) {
        let valores = Array.isArray(value) ? value : [value];
        this.acciones = Adel;
        this.newRow = 0;
        valores.forEach(v => {
            this.createRow(v);
        });
    }

    fillSelect(dataSet = this.dataSet) {
        // Permite iterar sobre dataSet tanto si es arreglo como si es objeto
        const items = Array.isArray(dataSet) ? dataSet : Object.values(dataSet);
        const options = this._structure.select;

        this._selector.html($('<option>', { value: '', text: '-- Seleccione --' }));
        items.forEach(item => {
            // Se obtiene el texto, ya sea mediante función o propiedad directa
            const text = typeof options.text === 'function' ? options.text(item) : (typeof item === 'string' ? item : item[options.text]);
            const value = typeof item === 'string' ? item : item[options.value];

            const { hidden, badge } = this._checkValidation(item);
            const atributos = {};

            if (hidden) {
                atributos['data-hidden'] = true;
                atributos['data-nosearch'] = true;
            }

            this._selector.append(
                $('<option>')
                    .val(value)
                    .text(text + (hidden && badge ? ` <label class="badge badge-danger ms-2">${badge}</label>` : ''))
                    .attr(atributos)
            );
            // item['use'] = false;
        });
    }

    _checkValidation(item) {
        for (const { clave, operation, value, badge = '' } of this._structure.select.validation) {
            if (clave in item && this.$operations[operation](item[clave], value)) {
                return { hidden: true, badge };
            }
        }
        return { hidden: false, badge: '' };
    }

    extract() {
        if (this._selector.val()) {
            boxAlert.box({ i: 'warning', t: 'Advertencia!', h: "El selector aun tiene un valor por añadir" });
            return false;
        }
        return this.data;
    }
}