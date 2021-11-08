import {createElement, createStyle, errorAlert, successAlert} from "../global/js/util.js";
import {Component} from "./Component.js";
import {modalBox} from "./ModalBox.js";

// language=CSS
createStyle()._content(`
    .PermisosModal .permisos {
        max-height: 500px;
        overflow: auto;
    }

    .PermisosModal .PermisoEntry {
        background-color: #dce0e2;
        cursor: pointer;
        transition: background-color .3s ease-in-out;
    }

    .PermisosModal .PermisoEntry i:before {
        font-family: "bootstrap-icons", serif;
        content: "\\f5d5";
        font-size: 1.2rem;
    }

    .PermisosModal .PermisoEntry.selected {
        background-color: var(--bs-primary);
        color: white;
    }

    .PermisosModal .PermisoEntry.selected i:before {
        font-family: "bootstrap-icons", serif;
        content: "\\f5d6";
    }
`);

/**
 *
 * @constructor
 */
export default function PermisosModal() {
    const _this = this;
    this.root = createElement('div')._class('PermisosModal')._html(`
        <div class="bg-white p-4">
            <h3 class="mb-4">
                <i class="bi-shield-fill me-2"></i>Permisos
            </h3>
            <div class="mb-4 css-loaded permisos" data-js="content">
                <!--permisos-->
            </div>
            <button type="button" class="btn btn-secondary p-2 w-100" data-js="button">
                <span>Cerrar</span>
            </button>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="content"]');
    const _buttons = _this.root.querySelectorAll('[data-js="button"]');
    let _config = {};

    /**
     * Constructor
     */
    function _constructor() {
        _buttons[0].onclick = ()=>{
            modalBox.close();
        };
    }

    /**
     *
     * @private
     */
    function _fetchPermisos() {
        fetch(`/api/usuarios/${_config['usuario'].id}/permisos`)
            .then(httpResp => httpResp.json())
            .then(response => {
                if (response.code === 200) {
                    _processData(response.data);
                } else {
                    errorAlert(response.error.message);
                }
            })
            .catch(reason => {
                errorAlert(reason);
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processData(data) {
        _content.innerHTML = "";
        data['permisos'].forEach(permiso => {
            const permisoEntry = new PermisoEntry(permiso);
            const found = data['permisosActivos'].find(permisoActivo => {
                return permisoActivo['nombre'] === permiso['nombre'];
            })
            if (found) permisoEntry.select();
            _content.append(permisoEntry.root);
        });
    }

    /**
     *
     * @param permiso
     * @constructor
     */
    function PermisoEntry(permiso) {
        const _this = this;
        this.root = createElement('div')._class('PermisoEntry')._html(`<div class="permission p-3 mb-2">
    <div class="row g-0">
        <div class="col">
            <p class="mb-0 mt-0 fw-bold">${permiso['nombre']}</p>
            <p class="mb-0 mt-0 d-block">${permiso['descripcion']}</p>
        </div>
        <div class="col-auto">
            <i class="switch"></i>
        </div>
    </div>
</div>`);

        /**
         * Constructor
         */
        function _constructor() {
            _this.root.onclick = _onSelect;
        }

        /**
         *
         * @private
         */
        function _onSelect() {
            if (_this.root.classList.contains('selected')) _removePermiso();
            else _assignPermiso();
        }

        /**
         *
         * @private
         */
        function _assignPermiso() {
            fetch(`/api/usuarios/${_config['usuario'].id}/permisos/${permiso.id}`, {
                method: 'POST'
            })
                .then(httpResp => httpResp.json())
                .then(response => {
                    if (response.code === 200) {
                        successAlert('El permiso se <b>asigno</b> con exito');
                        _this.root.classList.add('selected');
                    } else {
                        errorAlert(response.error.message);
                    }
                })
                .catch(reason => {
                    errorAlert(reason);
                });
        }

        /**
         *
         * @private
         */
        function _removePermiso() {
            fetch(`/api/usuarios/${_config['usuario'].id}/permisos/${permiso.id}`, {
                method: 'DELETE'
            })
                .then(httpResp => httpResp.json())
                .then(response => {
                    if (response.code === 200) {
                        successAlert('El permiso se <b>removio</b> con exito');
                        _this.root.classList.remove('selected');
                    } else {
                        errorAlert(response.error.message);
                    }
                })
                .catch(reason => {
                    errorAlert(reason);
                });
        }

        /**
         *
         */
        this.select = function() {
            _this.root.classList.add('selected');
        }

        //Invoke
        _constructor();
    }

    /**
     *
     */
    this.open = function() {
        modalBox.setComponent(_this);
        modalBox.open();
    }

    /**
     *
     */
    this.close = function() {
        modalBox.close();
    }

    /**
     *
     * @param config
     */
    this.setConfig = function(config) {
        _config = config;
        _fetchPermisos();
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(PermisosModal.prototype, new Component());