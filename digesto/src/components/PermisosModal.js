import {createElement, createStyle, errorAlert} from "../global/js/util.js";
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
        content: "\\f5d7";
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
            <div class="row g-2">
                <div class="col-sm">
                    <button type="button" class="btn btn-success p-2 w-100" data-js="button">
                        <span>Aceptar</span>
                    </button>
                </div>
                <div class="col-sm">
                    <button type="button" class="btn btn-secondary p-2 w-100" data-js="button">
                        <span>Cancelar</span>
                    </button>
                </div>
            </div>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="content"]');
    const _buttons = _this.root.querySelectorAll('[data-js="button"]');

    /**
     * Constructor
     */
    function _constructor() {
        _fetchPermisos();
        _buttons[0].onclick = _onSubmit;
        _buttons[1].onclick = _onCancel;
    }

    /**
     *
     * @private
     */
    function _fetchPermisos() {
        fetch(`/api/permisos`)
            .then(httpResp => httpResp.json())
            .then(response => {
                if (response.code === 200) {
                    _processPermisos(response.data['permisos']);
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
     * @param permisos
     * @private
     */
    function _processPermisos(permisos) {
        permisos.forEach(permiso => {
            _content.append(new PermisoEntry(permiso).root);
        });
    }

    /**
     *
     * @private
     */
    function _onSubmit(){

    }

    /**
     *
     * @private
     */
    function _onCancel(){
        modalBox.close();
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
            if (_this.root.classList.contains('selected')) {
                _this.root.classList.remove('selected');
            } else _this.root.classList.add('selected');
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

    //Invoke
    _constructor();
}

Object.setPrototypeOf(PermisosModal.prototype, new Component());