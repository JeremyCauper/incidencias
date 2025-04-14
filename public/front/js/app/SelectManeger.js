class CSelect {
    _this = null;
    _selector = null;
    _dataSet = null;
    _filterF = null;

    constructor(selector, options = {}) {
        this._selector = selector;
        this._dataSet = options.dataSet;
        this._filterV = options.filterValue;
        this._filterF = options.filterField;
        this._optionV = options.optionValue;
        this._optionT = options.optionText;
    }

    llenar() {
        $(this._selector.join()).html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
        if (!this._filterV) return false;
    
        if (Array.isArray(this._dataSet)) {
            this._dataSet.forEach(e => {
                if (e[_filterF] == this._filterV) {
                    $(this._selector[0]).append($('<option>').val(e[this._optionV]).text(e[this._optionT]));
                }
            });
        } else if (typeof this._dataSet === 'object') {
            Object.entries(this._dataSet).forEach(([key, e]) => {
                if (e[_filterF] == this._filterV) {
                    $(this._selector[0]).append($('<option>').val(e[this._optionV]).text(e[this._optionT]));
                }
            });
        }
        $(this._selector[0]).attr('disabled', false);
    }

    opcion() {
        // 
    }

    limpiar() {
        // 
    }
}