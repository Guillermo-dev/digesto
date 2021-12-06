import { createElement, createStyle, errorAlert } from "../global/js/util.js";
import { Component } from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminSearch button, .AdminSearch input, .AdminSearch .input-group-text {
        font-size: 0.9rem !important;
    }

    .AdminSearch .search-group input, .AdminSearch .search-group span {
        background-color: #00242a;
        border-color: #263d42;
        color: white;
    }

    .AdminSearch .search-group input {
        border-right: none
    }

    .AdminSearch .search-group input::placeholder {
        color: #A4A4A4;
    }

    .AdminSearch .drop-down-box {
        position: absolute;
        top: 70px;
        right: 0;
        color: black;
        z-index: 5;
        width: 100vw;
        max-width: 500px;
        transform: scale(0);
        opacity: 0;
        transition: opacity .2s ease-in-out 0s, transform 0s step-end .2s;
    }

    .AdminSearch .drop-down-box.visible {
        opacity: 1;
        transform: scale(1);
        transition: opacity .2s ease-in-out 0s, transform 0s step-end 0s;
    }

    .AdminSearch .drop-down-box > div {
        background-color: #ffffff;
        max-height: 500px;
        overflow-y: auto;
    }

    .AdminSearch .drop-down-box ul {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }

    .AdminSearch .drop-down-box ul li {
        padding: 10px;
        font-size: 0.9rem;
        overflow: hidden;
        text-align: left;
    }

    @media (max-width: 768px) {
        .AdminSearch .drop-down-box {
            width: 100%;
            max-width: 100%;
            top: 30px;
        }

        .AdminSearch .list-wrapper {
            position: relative;
        }
    }
`);

/**
 *
 * @constructor
 */
export default function AdminSearch() {
    const _this = this;
    this.root = createElement("div")._class("AdminSearch")
        ._html(`<div class="container p-3 px-2 position-relative">
    <div class="row g-0">
        <div class="col-md-auto">
            <form data-js="form">
                <div class="input-group search-group">
                    <input type="text" class="form-control p-2" placeholder="Buscar por titulo, numero" name="search" autocomplete="off">
                    <button type="submit" name="submitSearchBtn" class="bi-search btn btn-primary"></button>
                </div>
            </form>
        </div>
        <div class="col-md">
            <form data-js="form">
                <div class="row g-0 justify-content-end">
                    <div class="col-md-auto text-center list-wrapper">
                        <button type="button" name="tagBtn" class="btn w-100 text-white">
                            <b class="me-2">ETIQUETAS</b>
                            <span data-js="filtro">Todos</span>
                            <i class="ms-2 bi-chevron-down"></i>
                        </button>
                        <div class="drop-down-box p-2" data-js="drop-box">
                            <div class="p-2 shadow">
                                <ul class="list-unstyled m-0 p-0">
                                    <!--dynamic loading-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto text-center list-wrapper">
                        <button type="button" name="yearBtn" class="btn w-100 text-white">
                            <b class="me-2">AÑO</b>
                            <span data-js="filtro">Todos</span>
                            <i class="ms-2 bi-chevron-down"></i>
                        </button>
                        <div class="drop-down-box p-2" data-js="drop-box">
                            <div class="p-2 shadow">
                                <ul class="list-unstyled m-0 p-0">
                                    <!--dynamic loading-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto text-center list-wrapper">
                        <button type="button" name="emitterBtn" class="btn w-100 text-white">
                            <b class="me-2">EMISOR</b>
                            <span data-js="filtro">Todos</span>
                            <i class="ms-2 bi-chevron-down"></i>
                        </button>
                        <div class="drop-down-box p-2" data-js="drop-box">
                            <div class="p-2 shadow">
                                <ul class="list-unstyled m-0 p-0">
                                    <!--dynamic loading-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto text-center list-wrapper">
                        <button type="button" name="publicBtn" class="btn w-100 text-white">
                            <b class="me-2">PRIVACIDAD</b>
                            <span data-js="filtro">Todos</span>
                            <i class="ms-2 bi-chevron-down"></i>
                        </button>
                        <div class="drop-down-box p-2" data-js="drop-box">
                            <div class="p-2 shadow">
                                <ul class="list-unstyled m-0 p-0">
                                    <!--dynamic loading-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto text-center">
                        <button type="submit" class="btn btn-primary w-100 fw-bold" title="Filtrar">
                            <i class="bi-filter-left me-1"></i>
                            <span>Filtrar</span>
                        </button>
                    </div>
                    <div class="col-md-auto mt-2 mt-md-0 ms-md-2">
                        <button type="button" name="loginBtn" class="btn btn-danger w-100 bi-power" title="Cerrar sesión"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>`);

    const _forms = _this.root.querySelectorAll('[data-js="form"]');
    const _lists = _this.root.querySelectorAll('[data-js="drop-box"] ul');
    const _buttons = _forms[1].querySelectorAll('button[type="button"]');

    let _currentOpenedDropBox = null;
    let _documentosComponent = null;

    /**
     * Constructor
     */
    function _constructor() {
        document.body.addEventListener("click", function () {
            if (_currentOpenedDropBox)
                _currentOpenedDropBox.classList.remove("visible");
            _currentOpenedDropBox = null;
        });
        Array.from(_buttons).forEach((button) => {
            button.onclick = _onOpenDropBox;
        });
        _forms[0].onsubmit = (event) => {
            try {
                _onSubmitSearch.call(_forms[0], event);
            } catch (error) {
                errorAlert(error);
            }
            return false;
        };
        _forms[1].onsubmit = (event) => {
            try {
                _onSubmitFilter.call(_forms[1], event);
            } catch (error) {
                errorAlert(error);
            }
            return false;
        };
        _forms[1]["loginBtn"].onclick = () => {
            location.href = "/auth/logout";
        };
        _fetchEtiquetas();
        _fetchEmisores();
        _fillYears();
        _fillPublicos();
    }

    /**
     *
     */
    function _fillYears() {
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year >= 2016; year--) {
            _lists[1].append(_createFilterOption("anios", year));
        }
    }

    /**
     *
     */
    function _fillPublicos() {
        _lists[3].append(
            _createFilterOption("Privacidad", "Ver solo publicos")
        );
        _lists[3].append(
            _createFilterOption("Privacidad", "Ver solo privados")
        );
    }

    /**
     *
     * @private
     */
    function _fetchDocumentos() {
        _documentosComponent.setLoading();
        fetch("/api/documentos?visible=")
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _documentosComponent.processDocumentos(
                        response.data["documentos"]
                    );
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
    function _fetchEtiquetas() {
        fetch("/api/tags?usedOnly=")
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processFilterData(response.data);
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
    function _fetchEmisores() {
        fetch("/api/emisores")
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processFilterData(response.data);
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
    function _processFilterData(data) {
        if (data["tags"] !== undefined)
            data["tags"].forEach((tag) => {
                _lists[0].append(
                    _createFilterOption("etiquetas", tag["nombre"])
                );
            });

        if (data["emisores"] !== undefined)
            data["emisores"].forEach((emisor) => {
                _lists[2].append(
                    _createFilterOption("emisores", emisor["nombre"])
                );
            });
    }

    /**
     *
     * @param event
     * @private
     */
    function _onOpenDropBox(event) {
        event.cancelBubble = true;

        const dropBox = this.parentElement.querySelector(
            '[data-js="drop-box"]'
        );
        dropBox.onclick = function (event) {
            event.cancelBubble = true;
        };

        if (!dropBox.classList.contains("visible")) {
            dropBox.classList.add("visible");
            if (_currentOpenedDropBox && _currentOpenedDropBox !== dropBox)
                _currentOpenedDropBox.classList.remove("visible");
            _currentOpenedDropBox = dropBox;
        } else {
            dropBox.classList.remove("visible");
            _currentOpenedDropBox = null;
        }
    }

    /**
     *
     * @param event
     * @private
     */
    function _onSelectOption(event) {
        const list = this.parentElement.parentElement;
        const button = list.parentElement.parentElement.previousElementSibling;
        const checkedInputs = Array.from(list.querySelectorAll("input")).filter(
            (input) => {
                return input.checked;
            }
        );
        if (checkedInputs.length === 0) {
            button.children[1].textContent = "Todos";
        } else if (checkedInputs.length === 1) {
            button.children[1].textContent = checkedInputs[0].value;
        } else {
            button.children[1].textContent = `(${checkedInputs.length} Seleccionados)`;
        }
    }

    /**
     *
     * @param event
     * @private
     */
    function _onSubmitSearch(event) {
        const url = new URLSearchParams();
        url.append("visible", "");
        _documentosComponent.setLoading();

        if (_forms[0]["search"].value.length > 0)
            url.append("search", _forms[0]["search"].value);
        else {
            history.pushState(null, "", `/admin`);
            _fetchDocumentos();
            return false;
        }

        fetch(`/api/documentos?${url.toString()}`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                _clearFilters();
                if (response.code === 200) {
                    history.pushState(null, "", `/admin?${url.toString()}`);
                    _documentosComponent.processDocumentos(
                        response.data["documentos"]
                    );
                } else {
                    _documentosComponent.setError();
                    errorAlert(response.error.message);
                }
            })
            .catch((reason) => {
                _clearFilters();
                _documentosComponent.setError();
                errorAlert(reason);
            });
    }

    /**
     *
     * @private
     */
    function _clearFilters() {
        Array.from(
            _forms[1].querySelectorAll('span[data-js="filtro"]')
        ).forEach((span) => {
            span.textContent = "Todos";
        });
        Array.from(
            _forms[1].querySelectorAll('input[name="etiquetas"]')
        ).forEach((option) => {
            if (option.checked) {
                option.checked = false;
            }
        });
        Array.from(_forms[1].querySelectorAll('input[name="anios"]')).forEach(
            (option) => {
                if (option.checked) {
                    option.checked = false;
                }
            }
        );
        Array.from(
            _forms[1].querySelectorAll('input[name="emisores"]')
        ).forEach((option) => {
            if (option.checked) {
                option.checked = false;
            }
        });
        Array.from(
            _forms[1].querySelectorAll('input[name="Privacidad"]')
        ).forEach((option) => {
            if (option.checked) {
                option.checked = false;
            }
        });
    }

    /**
     *
     * @param event
     * @returns {boolean}
     * @private
     */
    function _onSubmitFilter(event) {
        const url = new URLSearchParams();
        url.append("visible", "");
        _documentosComponent.setLoading();

        const etiquetas = [];
        const anios = [];
        const emisores = [];
        const privacidad = [];

        _forms[1]
            .querySelectorAll('input[name="etiquetas"]')
            .forEach((option) => {
                if (option.checked) {
                    etiquetas.push(option.value);
                }
            });
        _forms[1].querySelectorAll('input[name="anios"]').forEach((option) => {
            if (option.checked) {
                anios.push(option.value);
            }
        });
        _forms[1]
            .querySelectorAll('input[name="emisores"]')
            .forEach((option) => {
                if (option.checked) {
                    emisores.push(option.value);
                }
            });
        _forms[1]
            .querySelectorAll('input[name="Privacidad"]')
            .forEach((option) => {
                if (option.checked) {
                    if (option.value === "Ver solo publicos") {
                        privacidad.push("publicos");
                    } else if (option.value === "Ver solo privados") {
                        privacidad.push("privados");
                    }
                }
            });

        if (etiquetas.length > 0) url.append("etiquetas", etiquetas.join(";"));

        if (anios.length > 0) url.append("anios", anios.join(";"));

        if (emisores.length > 0) url.append("emisores", emisores.join(";"));

        if (_forms[0]["search"].value.length > 0)
            url.append("search", _forms[0]["search"].value);

        if (privacidad.length > 0) {
            if (privacidad.length == 1) {
                if (privacidad.includes("publicos"))
                    url.append("privacidad", "publicos");
                else if (privacidad.includes("privados"))
                    url.append("privacidad", "privados");
            } else {
                url.append("privacidad", "all");
            }
        }

        let _url = "";
        if (url.toString().length > 0) _url = `?${url.toString()}`;

        fetch(`/api/documentos${_url}`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    history.pushState(null, "", `/admin${_url}`);
                    _documentosComponent.processDocumentos(
                        response.data["documentos"]
                    );
                } else {
                    _documentosComponent.setError();
                    errorAlert(response.error.message);
                }
            })
            .catch((reason) => {
                _documentosComponent.setError();
                errorAlert(reason);
            });
    }

    /**
     *
     * @param name
     * @param value
     * @returns {*}
     * @private
     */
    function _createFilterOption(name, value) {
        const element = createElement("li")._html(`
        <div class="row g-0 flex-nowrap">
            <div class="col-auto">
                <input id="${value}" name="${name}" type="checkbox" class="me-2" value="${value}">
            </div>
            <div class="col text-start">
                <label for="${value}">${value}</label>
            </div>
        </div>
    `);
        element.firstElementChild.onchange = _onSelectOption;
        return element;
    }

    /**
     *
     * @param documentosComponent
     */
    this.setDocumentosComponent = function (documentosComponent) {
        _documentosComponent = documentosComponent;
        _fetchDocumentos();
    };

    //Invoke
    _constructor();
}

Object.setPrototypeOf(AdminSearch.prototype, new Component());
