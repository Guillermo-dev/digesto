import {createElement, createStyle, errorAlert} from "../global/js/util.js";
import PermisosModal from "./PermisosModal.js";
import {Component} from "./Component.js";

// language=CSS
createStyle()._content(`
    .Usuarios:not(.css-loaded) .css-loaded,
    .Usuarios:not(.css-loading) .css-loading,
    .Usuarios:not(.css-error) .css-error {
        display: none !important;
    }

    .Usuarios {
        padding: 30px 0;
        height: 100%;
    }

    .Usuarios .UsuarioEntry {
        box-shadow: 0 4px 4px 1px #18363d33;
    }
`);

/**
 *
 * @returns {*}
 */
function goBackButton() {
    const element = createElement('div')._html(`
        <div class="container p-2">
            <button class="btn btn-primary btn-sm"><i class="bi-house me-2"></i>Volver</button>
        </div>    
    `);
    element.firstElementChild.onclick = () => {
        location.href="/admin"
    }
    return element;
}

/**
 *
 * @constructor
 */
export default function Usuarios() {
    const _this = this;
    this.root = createElement('div')._class('Usuarios')._html(`
        <!--New-->
        <div class="container mb-4">
            <div class="p-4 bg-light">
                <h5 class="text-muted"><i class="bi-person-fill me-2"></i>Agregar usuario</h5>
                <form data-js="form" class="row g-2">
                    <div class="col">
                        <input type="email" name="email" class="form-control p-2" placeholder="Email" required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success btn-sm p-2">
                            <span><i class="bi-plus-lg me-2"></i> Agregar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!--Loaded-->
        <div class="container css-loaded">
            <div data-js="content"><!----></div>
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
    const _ext = document.getElementById('UsuariosExt');
    const _form = _this.root.querySelector('[data-js="form"]');
    const _permisosModal = new PermisosModal();

    /**
     * Constructor
     */
    function _constructor() {
        _form.onsubmit = (event) => {
            event.preventDefault();
            _onSubmit.call(_form, event);
            return false;
        }
        _ext.append(goBackButton());
        _fetchUsuarios();
    }

    /**
     *
     * @param event
     * @private
     */
    function _onSubmit(event) {
        fetch('/api/usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({email: _form['email'].value})
        }).then(httpResp => httpResp.json()).then(response => {
            if (response.code === 200) {
                window['iziToast'].success({message: 'El usuario se agrego con exito'});
                _fetchUsuarios();
                _form.reset();
            } else {
                window['iziToast'].error({message: response.error.message});
            }
        }).catch(reason => {
            window['iziToast'].error({message: reason});
        });
    }

    /**
     *
     * @private
     */
    function _fetchUsuarios() {
        _this.setClassState('css-loading');
        _content.innerHTML = "";
        fetch(`/api/usuarios`)
            .then(httpResp => httpResp.json())
            .then(response => {
                if (response.code === 200) {
                    _this.setClassState('css-loaded');
                    _processUsuarios(response.data['usuarios']);
                } else {
                    _this.setClassState('css-error');
                    errorAlert(response.error.message);
                }
            })
            .catch(reason => {
                _this.setClassState('css-error');
                errorAlert(reason);
            });
    }

    /**
     *
     * @param usuarios
     * @private
     */
    function _processUsuarios(usuarios) {
        usuarios.forEach(usuario => {
            _content.append(new UsuarioEntry(usuario).root);
        });
    }

    /**
     *
     * @param usuario
     * @constructor
     */
    function UsuarioEntry(usuario) {
        const _this = this;
        this.root = createElement('div')._class('UsuarioEntry')._html(`
            <div class="p-4 mb-3 border">
                <div class="row g-2">
                    <div class="col-sm">
                        <p class="mb-0 fw-bold">${usuario["nombre"]} ${usuario["apellido"]}</p>
                        <p class="text-muted mb-0">${usuario["email"]}</p>
                    </div>
                    <div class="col-sm-auto text-end">
                        <button type="button" data-js="button" class="btn btn-warning btn-sm">
                            <i class="bi-shield-fill me-2"></i><span>Permisos</span>
                        </button>
                        <button type="button" data-js="button" class="btn btn-danger btn-sm">
                            <i class="bi-trash-fill me-2"></i><span>Eliminar</span>
                        </button>
                    </div>
                </div>
            </div>
        `);
        const _buttons = _this.root.querySelectorAll('[data-js="button"]');

        /**
         * Constructor
         */
        function _constructor() {
            _buttons[0].onclick = _onClickPermisos;
            _buttons[1].onclick = _onDelete;
        }

        /**
         *
         * @private
         */
        function _onClickPermisos() {
            _permisosModal.setConfig({
                usuario: usuario
            })
            _permisosModal.open();
        }

        /**
         *
         * @private
         */
        function _onDelete() {
            window.Sweetalert2.fire({
                icon: 'question',
                title: '¿Eliminar usuario?',
                text: 'Esta accion no se podra deshacer',
                cancelButtonText: 'No, lo pensaré',
                confirmButtonText: 'Si, estoy seguro!',
                showCancelButton: true,
                showCloseButton: true,
            }).then(result => {
                if (result.isConfirmed) fetch(`/api/usuarios/${usuario.id}`, {method: 'DELETE'})
                    .then(httpResp => httpResp.json())
                    .then(response => {
                        if (response.code === 200) {
                            window['iziToast'].success({message: 'El usuario se elimino con exito'});
                            _this.root.remove();
                        } else {
                            errorAlert(response.error.message);
                        }
                    })
                    .catch(reason => {
                        errorAlert(reason);
                    })
            });
        }

        //Invoke
        _constructor();
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(Usuarios.prototype, new Component());