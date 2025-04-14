class CSelect {
    $select = null;
    constructor(selector, { dataSet, filterField, optionValue = 'id', optionText, optionEstatus = null, optionSelected = null } = {}) {
        this.$select = $(selector[0]);
        this.selector = selector;
        this.dataSet = dataSet;
        this.filterField = filterField;
        this.optionValue = optionValue;
        this.optionText = optionText;
        this.optionEstatus = optionEstatus;
        this.optionSelected = optionSelected;
    }

    llenar(filterValue = null) {
        let value = typeof filterValue === 'function' ? filterValue() : filterValue;
        // Se inicia el select con la opción por defecto y se desactiva
        this.$select.html($('<option>', { value: '', text: '-- Seleccione --' })).attr('disabled', true);
        if (!value) return false;

        // Permite iterar sobre dataSet tanto si es arreglo como si es objeto
        const items = Array.isArray(this.dataSet) ? this.dataSet : Object.values(this.dataSet);
        items.forEach(item => {
            if (item[this.filterField] == value) {
                // Se obtiene el texto, ya sea mediante función o propiedad directa
                const text = typeof this.optionText === 'function' ? this.optionText(item) : item[this.optionText];
                let badge = '';
                const atributos = {};
                if (this.optionEstatus && this.optionEstatus == 0) {
                    atributos['data-hidden'] = true;
                    atributos['data-nosearch'] = true;
                    badge = '<label class="badge badge-danger ms-2">ED</label>';
                } else if (this.optionSelected == 1) {
                    atributos['selected'] = '';
                }
                this.$select.append($('<option>').val(item[this.optionValue]).text(text).attr(atributos));
            }
        });
        this.$select.attr('disabled', false);
    }
}