import { createElement, createStyle, errorAlert, successAlert } from "../global/js/util.js";
import { Component } from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminDocumentos:not(.css-loaded) .css-loaded,
    .AdminDocumentos:not(.css-loading) .css-loading,
    .AdminDocumentos:not(.css-no-entries) .css-no-entries,
    .AdminDocumentos:not(.css-error) .css-error,
    .AdminDocumentos:not(.css-more-btn) .css-more-btn {
        display: none !important;
    }

    .AdminDocumentos {
        padding: 30px 0;
        height: 100%;
    }

    .AdminDocumentos .DocumentoEntry {
        box-shadow: 0 4px 4px 1px #18363d33;
    }

    .AdminDocumentos .DocumentoEntry:not(.private) i[class~="bi-eye-fill"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry:not(.private) span[class~="badgeP"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry.private {
        opacity: 0.5;
    }

    .AdminDocumentos .DocumentoEntry.private i[class~="bi-eye-slash-fill"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry.noDescargable i[class~="bi-shield-slash-fill"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry:not(.noDescargable) i[class~="bi-shield-fill"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry:not(.noDescargable) span[class~="badgeD"] {
        display: none;
    }

    .AdminDocumentos .DocumentoEntry.derogado span[class~="badgeDe"] {
        display: none;
    }

    .AdminDocumentos .more-btn {
        background-color: #f6f6f6;
        cursor: pointer;
        transition: background-color .5s ease-in-out;
    }

    .AdminDocumentos .more-btn:hover {
        background-color: #e6e8ea;
    }
`);

/**
 *
 * @constructor
 */
export default function AdminDocumentos() {
    const _this = this;
    this.root = createElement('div')._class('AdminDocumentos')._html(`
        <!--New-->
        <div class="container">
            <div class="container p-3 text-center more-btn text-secondary mb-2" data-js="new-btn">
                <i class="bi-plus-lg me-3"></i>Agregar nuevo documento
            </div>
        </div>
        <!--Loaded-->
        <div class="container css-loaded">
            <div data-js="content">
                <!---->
            </div>
            <div class="container p-3 text-center more-btn css-more-btn text-secondary" data-js="more-btn">
                <i class="bi-search me-3"></i>Mostrar mas resultados
            </div>
        </div>
        <!--No entries-->
        <div class="css-no-entries p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <img src="/src/global/images/empty-state.svg" class="mb-3" width="150" alt="Sin resultados">
            <p class="text-muted text-center">No se encontraron resultados</p>
        </div>
        <!--Error-->
        <div class="css-error p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <img src="/src/global/images/500.png" class="mb-3" width="400" alt="Sin resultados">
        </div>
        <!--Loading-->
        <div class="css-loading p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <span class="spinner-border"></span>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="content"]');
    const _newBtn = _this.root.querySelector('[data-js="new-btn"]');
    const _moreBtn = _this.root.querySelector('[data-js="more-btn"]');
    const _paginator = {
        window: 5,
        length: 0,
        i: 0,
    };

    /**
     * Constructor
     */
    function _constructor() {
        _newBtn.onclick = () => {
            location.href = '/admin/documento/';
        }
        _moreBtn.onclick = _paginate;
    }

    /**
     *
     * @private
     */
    function _paginate() {
        let from = _paginator.i;

        let to = from + _paginator.window
        if (to >= _paginator.length)
            to = _paginator.length;

        while (from < to)
            _content.children[from++].classList.remove('d-none');

        _paginator.i = from;

        if (_paginator.i < _paginator.length)
            _this.setClassState('css-more-btn', 1);
        else _this.removeClassState(1);
    }

    /**
     *
     */
    this.setLoading = function() {
        _this.setClassState('css-loading');
    };

    /**
     *
     */
    this.setError = function() {
        _this.setClassState('css-error');
    };

    /**
     *
     */
    this.processDocumentos = function(documentos) {
        _content.innerHTML = "";
        if (documentos.length > 0) {
            documentos.forEach(documento => {
                _content.append(new DocumentoEntry(documento).root);
            });
            _this.setClassState('css-loaded');

            _paginator.length = documentos.length;
            _paginator.i = 0;
            _paginate();
        } else _this.setClassState('css-no-entries');
    };

    /**
     *
     * @param documento
     * @constructor
     */
    function DocumentoEntry(documento) {
        const _this = this;
        const fecha = new Date(documento["fechaEmision"]).toLocaleString().split(" ")[0];
        this.root = createElement('div')._class('DocumentoEntry')._html(`
            <div class="p-4 mb-3 border document">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <p class="mb-0 fw-bold">${documento["titulo"] + " - " + documento["numeroExpediente"]} 
                        <span class="badge badgeP bg-secondary ms-1">Privado</span>
                        <span class="badge badgeD bg-secondary ms-1">No descargable</span>
                        <span class="badge badgeDe bg-secondary ms-1">Derogado</span>
                        </p>
                    </div>
                    <div class="col-auto small"><i class="bi-calendar3 me-2"></i>${fecha}</div>
                </div>
                <p class="mb-0">Descripción</p>
                <p class="mb-4 mb-sm-2 text-muted">${documento["descripcion"]}</p>

                <p class="mb-0"> Etiquetas</p>
                <div class="d-flex flex-wrap mb-0" data-js="etiquetas"> 
                    <!--tags -->
                </div>

                <div class="text-end">
                    <button type="button" data-js="button" class="btn btn-sm btn-warning">
                        <i class="bi-eye-fill me-1" title="Hacer publico"></i>
                        <i class="bi-eye-slash-fill me-1" title="Hacer privado"></i>
                        <span>Visibilidad</span>
                    </button>

                    <button type="button" data-js="button" class="btn btn-sm btn-warning">
                        <i class="bi-shield-fill me-1" title="Hacer publico"></i>
                        <i class="bi-shield-slash-fill me-1" title="Hacer privado"></i>
                        <span>Descargable</span>
                    </button>

                    <button type="button" data-js="button" class="btn btn-sm btn-warning">
                        <i class="bi-pencil me-1"></i>
                        <span>Editar</span>
                    </button>
                    <button type="button" data-js="button" class="btn btn-sm btn-danger">
                        <i class="bi-trash me-1"></i>
                        <span>Eliminar</span>
                    </button>
                </div>
            </div>
        `);
        const _buttons = _this.root.querySelectorAll('[data-js="button"]');
        const etiquetas = _this.root.querySelector('[data-js="etiquetas"]');

        documento.tags.forEach((tag, i) => {
            if (i == documento.tags.length-1) {
                etiquetas.innerHTML += `<p class="text-muted me-2 mb-0">${tag.nombre}</p>`;
            } else {
                etiquetas.innerHTML += `<p class="text-muted me-2 mb-0">${tag.nombre} -</p>`;
            }
        });

        /**
         * Constructor
         */
        function _constructor() {
            if (!documento['publico']) _this.root.classList.add('private');
            if (!documento['descargable']) _this.root.classList.add('noDescargable');
            if (!documento['derogado']) _this.root.classList.add('derogado');
            _this.root.classList.add('d-none');
            _buttons[0].onclick = _onChangeVisibility;
            _buttons[1].onclick = _onChangeDescargable;
            _buttons[2].onclick = function() {
                location.href = `/admin/documento/${documento.id}`;
            }
            _buttons[3].onclick = _onDelete
        }

        /**
         *
         * @private
         */
        function _onDelete() {
            window.Sweetalert2.fire({
                icon: 'question',
                title: '¿Eliminar documento?',
                text: 'Esta accion no se podra deshacer',
                confirmButtonText: 'Si, estoy seguro!',
                cancelButtonText: 'No, lo pensaré',
                reverseButtons:true,
                showCancelButton: true,
                showCloseButton: true,
            }).then(result => {
                if (result.isConfirmed) fetch(`/api/documentos/${documento.id}`, { method: 'DELETE' })
                    .then(httpResp => httpResp.json())
                    .then(response => {
                        if (response.code === 200) {
                            _this.root.remove();
                            successAlert(`Se <b>elimino</b> el documento <b>${documento.numeroExpediente}</b> con exito`);
                        } else {
                            errorAlert(response.error.message);
                        }
                    })
                    .catch(reason => {
                        errorAlert(reason);
                    })
            });
        }

        /**
         *
         * @private
         */
        function _onChangeVisibility() {
            if (documento['publico']) {
                window.Sweetalert2.fire({
                    icon: 'question',
                    title: '¿Cambiar visibilidad?',
                    html: 'Vas a cambiar la visibilidad del documento a <b>Privado</b>',
                    cancelButtonText: 'No, lo pensaré',
                    confirmButtonText: 'Si, estoy seguro!',
                    showCancelButton: true,
                    showCloseButton: true,
                    reverseButtons:true,
                }).then(result => {
                    if (result.isConfirmed) _changeVisibility(false, () => {
                        successAlert('Cambiaste la visibilidad del documento a <b>Privado</b>');
                        _this.root.classList.add('private');
                        documento['publico'] = false;
                    })
                });
            } else {
                window.Sweetalert2.fire({
                    icon: 'question',
                    title: '¿Cambiar visibilidad?',
                    html: 'Vas a cambiar la visibilidad del documento a <b>Publico<b>',
                    cancelButtonText: 'No, lo pensaré',
                    confirmButtonText: 'Si, estoy seguro!',
                    reverseButtons:true,
                    showCancelButton: true,
                    showCloseButton: true,
                }).then(result => {
                    if (result.isConfirmed) _changeVisibility(true, () => {
                        successAlert('Cambiaste la visibilidad del documento a <b>Publico</b>');
                        _this.root.classList.remove('private');
                        documento['publico'] = true;
                    })
                });
            }
        }

        /**
         *
         * @param publico
         * @param fn
         * @private
         */
        function _changeVisibility(publico, fn) {
            const formData = new FormData();
            formData.append('publico',publico);
            fetch(`/api/documentos/${documento.id}`, {method: 'POST', body: formData})
            .then(httpResp => httpResp.json())
            .then(response => {
                if (response.code === 200) {
                    fn();
                } else {
                    errorAlert(response.error.message);
                }
            })
            .catch(reason => {
                errorAlert(reason);
            })
        }

        /**
         *
         * @private
         */
        function _onChangeDescargable(){
            if (documento['descargable']) {
                window.Sweetalert2.fire({
                    icon: 'question',
                    title: '¿Cambiar descargable?',
                    html: 'Vas a cambiar el estado del documento a <b>No descargable</b>',
                    cancelButtonText: 'No, lo pensaré',
                    confirmButtonText: 'Si, estoy seguro!',
                    showCancelButton: true,
                    showCloseButton: true,
                    reverseButtons:true,
                }).then(result => {
                    if (result.isConfirmed) _changeDescargable(false, () => {
                        successAlert('Cambiaste el estado del documento a <b>No descargable</b>');
                        _this.root.classList.add('noDescargable');
                        documento['descargable'] = false;
                    })
                });
            } else {
                window.Sweetalert2.fire({
                    icon: 'question',
                    title: '¿Cambiar descargable?',
                    html: 'Vas a cambiar el estado del documento a <b>Descargable</b>',
                    cancelButtonText: 'No, lo pensaré',
                    confirmButtonText: 'Si, estoy seguro!',
                    reverseButtons:true,
                    showCancelButton: true,
                    showCloseButton: true,
                }).then(result => {
                    if (result.isConfirmed) _changeDescargable(true, () => {
                        successAlert('Cambiaste el estado del documento a <b>Descargable</b>');
                        _this.root.classList.remove('noDescargable');
                        documento['descargable'] = true;
                    })
                });
            }
        }

        function _changeDescargable(descargable, fn){
            const formData = new FormData();
            formData.append('descargable',descargable);
            fetch(`/api/documentos/${documento.id}`, {method: 'POST', body: formData})
            .then(httpResp => httpResp.json())
            .then(response => {
                if (response.code === 200) {
                    fn();
                } else {
                    errorAlert(response.error.message);
                }
            })
            .catch(reason => {
                errorAlert(reason);
            })
        }
        //Invoke
        _constructor()
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(AdminDocumentos.prototype, new Component());