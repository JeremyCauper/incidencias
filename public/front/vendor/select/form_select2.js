$(document).ready(function () {
    const selectConfig = {
        "select": {
            minimumResultsForSearch: Infinity
        },
        "select_search": {}
    };

    // Función para inicializar select2
    function initializeSelect2(selectElement, config, modal) {
        selectElement.select2({
            ...config,
            dropdownParent: modal
        });
    }

    // Inicialización por defecto
    $('.select').each(function () {
        initializeSelect2($(this), selectConfig.select, $('body'));
    });

    // Inicialización con búsqueda
    $('.select-search').each(function () {
        initializeSelect2($(this), selectConfig.select_search, $('body'));
    });

    // Aplica select2 a todos los selects dentro de los modales al mostrarse
    $('.modal').on('shown.bs.modal', function () {
        const modal = $(this).closest('.modal');
        modal.find('select').each(function () {
            if ($(this).hasClass('select')) {
                initializeSelect2($(this), selectConfig.select, modal);
            }
            if ($(this).hasClass('select-search')) {
                initializeSelect2($(this), selectConfig.select_search, modal);
            }
        });
    });
});
