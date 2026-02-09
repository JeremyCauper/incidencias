$(document).ready(function () {
    const config = {
        "select": {
            placeholder: 'Seleccione...',
            allowClear: false,
        },
        "search": {
            search: true,
            placeholder: 'Seleccione...',
            searchPlaceholder: 'Buscar',
            allowClear: false,
            backdrop: true
        },
        "clear": {
            search: true,
            placeholder: 'Seleccione...',
            searchPlaceholder: 'Buscar',
            allowClear: true,
            backdrop: true
        },
        "clear_simple": {
            placeholder: 'Seleccione...',
            allowClear: true,
        },
        "tags": {
            search: true,
            placeholder: 'Buscar',
            allowClear: true,
            tags: true,
            createTag: function (params) {
                // Creamos un nuevo objeto tag con el value que queramos
                return {
                    id: 'nuevo:' + params.term,          // Este será el value
                    text: params.term,    // Este será el texto mostrado
                    newOption: true       // Lo marcamos como nuevo si queremos estilos
                };
            },
        },
        "icons": {
            placeholder: 'Seleccione...',
            allowClear: true,
        }
    };

    // Función para inicializar select2
    function initializeSelect2(selectElement, config, modal) {
        selectElement.customSelect2({
            ...config,
            // matcher: matchCustom,
            templateResult: function (data) {
                if ($(data.element).data('hidden')) {
                    return null;
                }
                return data.text;
            },
            templateSelection: function (data) {
                return data.text;
            }
        });
    }

    /*function matchCustom(params, data) {
        // Si no hay término de búsqueda, retorna el dato para mostrarlo
        if ($.trim(params.term) === '') {
            return data;
        }

        // Excluir la opción si tiene data-nosearch="true"
        if ($(data.element).data('nosearch')) {
            return null;
        }

        // Búsqueda estándar: si el texto de la opción coincide, la retorna
        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            return data;
        }

        // No coincide, excluir la opción
        return null;
    }*/

    // Inicialización por defecto
    $('.select').each(function () {
        initializeSelect2($(this), config.select);
    });

    // Inicialización con búsqueda
    $('.select-search').each(function () {
        initializeSelect2($(this), config.search);
    });

    // Allow clear selection
    $('.select-clear').each(function () {
        initializeSelect2($(this), config.clear);
    });

    // Allow clear selection
    $('.select-clear-simple').each(function () {
        initializeSelect2($(this), config.clear_simple);
    });

    // Allow tags selection
    $('.select-tags').each(function () {
        initializeSelect2($(this), config.tags);
    });

    // Allow icons selection
    $('.select-icons').each(function () {
        initializeSelect2($(this), config.icons);
    });
});