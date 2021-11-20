import {
    createElement,
    createStyle,
    errorAlert,
    successAlert,
    warningAlert,
} from "../global/js/util.js";
import { Component } from "./Component.js";
import { modalBox } from "./ModalBox.js";

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
    this.root = createElement("div")._class("PermisosModal")._html(`
        <div class="bg-white p-4">
            <h3 class="mb-4">
                <i class="bi-shield-fill me-2"></i>Permisos
            </h3>
            <div class="mb-4 css-loaded permisos" data-js="content">
                <!--permisos-->
            </div>
            <form data-js="form">
                <div class="row g-2">
                    <div class="col">
                        <button type="button" name="cancelBtn" class="btn btn-secondary p-2 w-100" data-js="button">
                            <span>Cerrar</span>
                        </button>
                    </div>
                    <div class="col">
                        <button type="submit" name="submitBtn" class="btn btn-success p-2 w-100" data-js="button">
                            <span>Aceptar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="content"]');
    const _form = _this.root.querySelector('[data-js="form"]');
    let _config = {};

    /**
     * Constructor
     */
    function _constructor() {
        _form["submitBtn"].onclick = (event) => {
            event.preventDefault();
            _onSubmit.call(_form, event);
            return false;
        };
        _form["cancelBtn"].onclick = () => {
            modalBox.close();
        };
    }

    /**
     *
     * @private
     */
    function _fetchPermisos() {
        fetch(`/api/usuarios/${_config["usuario"].id}/permisos`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processData(response.data);
                } else {
                    errorAlert(response.error.message);
                }
            })
            .catch((reason) => {
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
        data["permisos"].forEach((permiso) => {
            const permisoEntry = new PermisoEntry(permiso);
            const found = data["permisosActivos"].find((permisoActivo) => {
                return permisoActivo["nombre"] === permiso["nombre"];
            });
            if (found) {
                permisoEntry.initialState = true;
                permisoEntry.select();
            }
            _content.append(permisoEntry.root);
            permisoEntry.root._self = permisoEntry;
        });
    }

    /**
     *
     * @param event
     * @private
     */
    function _onSubmit(event) {
        const assignList = [];
        const removeList = [];

        Array.from(_content.children).forEach((entry) => {
            if (entry._self.initialState && !entry._self.finalState)
                removeList.push(entry._self.getPermiso().id);
            else if (!entry._self.initialState && entry._self.finalState)
                assignList.push(entry._self.getPermiso().id);
        });

        if (assignList.length === 0 && removeList.length === 0) {
            warningAlert("No se realizaron cambios en los permisos");
            return;
        }

        let requestData = {};

        if (assignList.length > 0) requestData.assign = assignList;
        if (removeList.length > 0) requestData.remove = removeList;

        fetch(`/api/usuarios/${_config.usuario.id}/permisos`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        })
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    successAlert("Los permisos se actualizaron con exito");
                    Array.from(_content.children).forEach((entry) => {
                        if (entry._self.initialState && !entry._self.finalState)
                            entry._self.initialState = false;
                        else if (
                            !entry._self.initialState &&
                            entry._self.finalState
                        )
                            entry._self.initialState = true;
                    });
                    modalBox.close();
                    _clearComponent();
                } else {
                    errorAlert(response.error.message);
                }
            })
            .catch((reason) => {
                errorAlert(reason);
            });
    }

    /**
     *
     * @private
     */
    function _clearComponent() {
        _config = {};
    }

    /**
     *
     */
    this.open = function () {
        modalBox.setComponent(_this);
        modalBox.open();
    };

    /**
     *
     */
    this.close = function () {
        modalBox.close();
    };

    /**
     *
     * @param config
     */
    this.setConfig = function (config) {
        _config = config;
        _fetchPermisos();
    };

    /**
     *
     * @param permiso
     * @constructor
     */
    function PermisoEntry(permiso) {
        const _this = this;
        this.root = createElement("div")._class("PermisoEntry")
            ._html(`<div class="permission p-3 mb-2">
    <div class="row g-0">
        <div class="col">
            <p class="mb-0 mt-0 d-block">${permiso["descripcion"]}</p>
        </div>
        <div class="col-auto">
            <i class="switch"></i>
        </div>
    </div>
</div>`);
        this.initialState = false;
        this.finalState = false;

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
            if (_this.root.classList.contains("selected")) {
                _this.root.classList.remove("selected");
                _this.finalState = false;
            } else {
                _this.root.classList.add("selected");
                _this.finalState = true;
            }
        }

        /**
         *
         */
        this.select = function () {
            _this.root.classList.add("selected");
            _this.finalState = true;
        };

        /**
         *
         * @returns {*}
         */
        this.getPermiso = function () {
            return permiso;
        };

        //Invoke
        _constructor();
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(PermisosModal.prototype, new Component());
