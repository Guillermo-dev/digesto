import {
    createElement,
    createStyle,
    errorAlert,
    successAlert,
    warningAlert,
} from "../global/js/util.js";
import { Component } from "./Component.js";

// language=CSS
createStyle("AdminEditDocumento")._content(`
    .AdminEditDocumento:not(.css-loaded) .css-loaded,
    .AdminEditDocumento:not(.css-loading) .css-loading,
    .AdminEditDocumento:not(.css-error) .css-error {
        display: none !important;
    }

    .AdminEditDocumento .drag-zone {
        border: dashed #d6d7db 1px;
        background-color: #f1f1f5;
        color: #60636d;
        min-height: 150px;
        transition: border .3s ease-in-out;
    }

    .AdminEditDocumento .drag-zone.changed {
        border: dashed #9daade 4px;
    }

    .cuerpoDoc {
        width: auto;
        margin: auto;
        overflow: hidden;
    }

    .encabezadoDoc {
        background-color: rgb(204, 202, 202);
        color: black;
        width: auto;
        height: 40px;
        vertical-align: middle;
        padding: 5px 0 5px 10px;
    }

    .informacionDoc {
        width: auto;
        padding: 20px 10px 10px 20px;
        background-color: rgb(240, 240, 240);
    }

    .pContenedor {
        display: flex;
    }

    .pContenedor p:first-of-type {
        width: 120px;
    }

    .vistaDoc {
        background-color: rgb(240, 240, 240);
        padding: 10px 10px 10px 10px;
    }

    .contenedor {
        padding: 10px 10px 10px 10px;
        overflow: hidden;
        box-shadow: 0 4px 4px 1px #18363d33;
        width: auto;
        height: auto;
    }

    .cargaDocumento {
        display: flex;
    }

`);

/**
 *
 * @constructor
 */
export default function AdminEditDocumento() {
    const _this = this;
    this.name = "AdminEditDocumento";
    this.root = createElement("div")._class("AdminEditDocumento")
        ._html(`<!--Loaded-->
    <div class="container css-loaded">
        <div class="cuerpoDoc mt-4 border shadow mb-5 bg-white  rounded-top" id="cuerpoDoc">
            <div class="encabezadoDoc text-center" id="encabezadoDoc">
                <h3>Editar documento</h3>
            </div>
            <div data-js="content"><!----></div>
            <form class="p-4" data-js="form">
                <div class="row g-3 mb-3">
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="titulo">Título<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="titulo" required id="titulo" placeholder=""  autocomplete="off">
                    </div>
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="numero">Número<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="numeroExpediente" id="numero" required placeholder="" autocomplete="off">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Pequeño resumen del contenido del documento (opcional)"></textarea>
                    </div>
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="fecha">Fecha de emisión<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="fechaEmision" required id="fecha" placeholder="">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="tipo">Tipo<span class="text-danger">*</span></label>
                        <select class="form-control" name="tipo" id="tipo">
                            <!--carga dinamica-->
                            <option value="0">Agregar nuevo</option>
                        </select>
                    </div>
                    <div class="col-sm d-none">
                        <label class="fw-bold mt-2" for="nuevoTipo">Nuevo tipo</label>
                        <input type="text" name="nuevoTipo" class="form-control" id="nuevoTipo" autocomplete="off">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm">
                        <label class="fw-bold mt-2" for="emisor">Emisor<span class="text-danger">*</span></label>
                        <select class="form-control" name="emisor" id="emisor" required>
                            <!--carga dinamica-->
                            <option value="0">Agregar nuevo</option>
                        </select>
                    </div>
                    <div class="col-sm d-none">
                        <label class="fw-bold mt-2" for="nuevoEmisor">Nuevo Emisor</label>
                        <input type="text" name="nuevoEmisor" class="form-control" id="nuevoEmisor" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold mt-2" for="etiquetas">Etiquetas<span class="text-danger">*</span></label>
                    <input type="text" list="datalist" class="form-control mb-2" id="etiquetas" name="etiqueta" placeholder="Ingrese una etiqueta y presione ENTER para agregarla" autocomplete="off">
                    <datalist id="datalist" data-js="datalist-tags">
                        <!---->
                    </datalist>
                    <p>Se recomienda agregar entre 3 y 8 etiquetas por documento. Usar palabras representativas.</p>
                    <div class="mb-2" data-js="tag-zone">
                        <!--<span class="bg-primary p-2 text-white small rounded">Etiqueta 2 <i class="bi-x-lg ms-2" style="cursor:pointer;" title="Eliminar"></i></span>-->
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold mt-2" for="privacidad">Privacidad<span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publico" id="publico" value="1" checked>
                                <label class="form-check-label" for="publico">Público</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publico" id="Reservado" value="0">
                                <label class="form-check-label" for="Reservado">Reservado</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold mt-2" for="Estado">Estado<span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="descargable" id="Descargable" value="1" checked>
                                <label class="form-check-label" for="Descargable">Descargable </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="descargable" id="No descargable" value="0">
                                <label class="form-check-label" for="No descargable">No descargable</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold mt-2" for="derogado">Derogacion</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="derogar" id="noDerogado" value="1" checked>
                                <label class="form-check-label" for="noDerogado">No derogado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="derogar" id="derogado" value="0">
                                <label class="form-check-label" for="derogado">Derogado</label>
                            </div>
                        </div>
                        <div class="d-none" data-js="documentoDerogador">
                            <label class="fw-bold mt-2">Documento</label>
                            <input name="documentoDerogador" list="documentoDerogadorList" placeholder="Número de documento" autocomplete="off">
                            <datalist id ="documentoDerogadorList"  data-js="documentoDerogadorList">
                                <!--carga dinamica-->
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Cargar documento<span class="text-danger">*</span></label>
                    <div class="drag-zone p-5 d-flex justify-content-center align-items-center" data-js="drag-zone">
                        <div>
                            <p class="text-center">Arrastre aquí para subir un archivo</p>
                            <p class="text-center">o si lo prefiere</p>
                            <div class="text-center">
                                <button type="button" name="fileBtn" class="btn btn-primary p-2"><span>Haga click para subir un archivo</span></button>
                                <input type="file" name="file" hidden>
                            </div>
                            <div class="text-center text-muted mt-4 fw-bold d-none" data-js="file-text">
                                <span><!----></span><i style="cursor:pointer;" title="Eliminar" class="text-danger bi-x-lg ms-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-js="archivo"> 
                    <iframe data-js="NoTieneQueSerNecesariamenteLoQueQuieras" src="" class="frame" frameborder="5" width="100%" height="680px" ></iframe>
                </div>
                <p class="fw-bold text-danger mb-3">*Campos obligatorios</p>
                <div class="text-end">
                    <button type="button" class="btn btn-primary"  data-js="button">Cancelar</button>
                    <button name ="submitBtn" type="submit" class="btn btn-primary">Guardar<span class="ms-2 spinner-border spinner-border-sm d-none"></span></button>
                </div>
                </form>
            </div>
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
    const _tagZone = _this.root.querySelector('[data-js="tag-zone"]');
    const _dataListTags = _this.root.querySelector('[data-js="datalist-tags"]');
    const _dragZone = _this.root.querySelector('[data-js="drag-zone"]');
    const _fileText = _this.root.querySelector('[data-js="file-text"]');
    const _form = _this.root.querySelector('[data-js="form"]');
    const _archivoContainer = _this.root.querySelector('[data-js="archivo"]');
    const _pdf = _this.root.querySelector(
        '[data-js="NoTieneQueSerNecesariamenteLoQueQuieras"]'
    );
    const _documentoDerogadorForm = _this.root.querySelector(
        '[data-js="documentoDerogador"]'
    );
    const _exitButton = _this.root.querySelector('[data-js="button"]');

    const _url = window.location.pathname;
    const _id = _url.substring(_url.lastIndexOf("/") + 1);

    let _oldPath;
    let _file = null;
    let _tags = {};
    let _emisor = "";
    let _tipo = "";
    let _documentoDerogador = 0;
    /**
     * Constructor
     * @private
     */
    function _constructor() {
        _fetchTipos();
        _fetchEmisores();
        _fetchData(_fetchDocumentos);
        let counter = 0;
        _fileText.children[1].onclick = _onRemoveFile;
        _dragZone.ondragenter = function (event) {
            counter++;
            this.classList.add("changed");
        };
        _dragZone.ondragover = function (event) {
            event.preventDefault();
        };
        _dragZone.ondrop = function (event) {
            event.preventDefault();
            this.classList.remove("changed");
            if (event.dataTransfer.items.length === 1) {
                _onSelectFile(event.dataTransfer.items[0].getAsFile());
            } else {
                warningAlert("Solo se permite cargar 1 archivo pdf");
            }
        };
        _dragZone.ondragleave = function (event) {
            counter--;
            if (counter === 0) {
                this.classList.remove("changed");
            }
        };
        _form["tipo"].onchange = _onChangeTipos;
        _form["emisor"].onchange = _onChangeEmisores;
        _form["fileBtn"].onclick = function () {
            this.nextElementSibling.click();
        };
        _form["file"].onchange = function () {
            _onSelectFile(this.files[0]);
        };
        _form["etiqueta"].onkeydown = _onKeyDownEtiquetas;
        _form["etiqueta"].onchange = function (event) {
            event.keyCode = 13;
            _onKeyDownEtiquetas.call(_form["etiqueta"], event);
        };

        Array.from(_form["derogar"]).forEach((option) => {
            option.onchange = _onChangeDerogar;
        });

        _form.onsubmit = function (event) {
            try {
                _onSubmit.call(_form, event);
            } catch (reason) {
                errorAlert(reason.toString());
            }
            return false;
        };
        _fetchTags();
        _exitButton.onclick = () => {
            location.href = "/admin/";
        };
    }

    /**
     *
     * @private
     */
    function _fetchData(fn) {
        _this.setClassState("css-loading");
        fetch(`/api/documentos/${_id}`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processData(response.data);
                    fn();
                } else {
                    _this.setClassState("css-error");
                    errorAlert(response.error.message);
                }
            })
            .catch((reason) => {
                _this.setClassState("css-error");
                errorAlert(reason);
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processData(data) {
        _form["titulo"].value = data["documento"].titulo;
        _form["numeroExpediente"].value = data["documento"].numeroExpediente;
        _form["descripcion"].value = data["documento"].descripcion;
        _form["fechaEmision"].value = data["documento"].fechaEmision;

        _tipo = data["tipo"].nombre;
        _form["tipo"].value = _tipo;

        _emisor = data["emisor"].nombre;
        _form["emisor"].value = _emisor;

        data["tags"].forEach((tag) => {
            _tags[tag.nombre] = true;
            _tagZone.append(_createEtiqueta(tag.nombre));
        });
        data["documento"].descargable
            ? (_form["descargable"].value = 1)
            : (_form["descargable"].value = 0);
        data["documento"].publico
            ? (_form["publico"].value = 1)
            : (_form["publico"].value = 0);

        if (data["documento"].derogado) {
            _form["derogar"].value = 0;
            _documentoDerogador = data["documento"].derogado_id;
            _documentoDerogadorForm.classList.remove("d-none");
            _documentoDerogadorForm.disabled = false;
            _documentoDerogadorForm.required = true;
        }

        _oldPath = `../../../${data["pdf"].path}`;
        _pdf.src = _oldPath;
    }

    /**
     *
     * @param file
     * @private
     */
    function _onSelectFile(file) {
        _file = file;
        _fileText.children[0].textContent = _file.name;
        _fileText.classList.remove("d-none");

        _archivoContainer.classList.remove("d-none");
        _pdf.src = URL.createObjectURL(file);
    }

    /**
     *
     * @private
     */
    function _onRemoveFile() {
        _file = null;
        _fileText.classList.add("d-none");

        _form["file"].value = "";
        _pdf.src = _oldPath;
    }

    /**
     *
     * @param name
     * @private
     */
    function _createEtiqueta(name) {
        const etiqueta = createElement("span")._html(`
            <span style="background-color: var(--bs-primary);" class="d-inline-block mb-2 p-2 text-white small rounded me-2">
                ${name} <i class="bi-x-lg ms-2" style="cursor:pointer;" title="Eliminar"></i>
            </span>
        `).firstElementChild;
        etiqueta._name = name;
        etiqueta.firstElementChild.onclick = function () {
            this.parentElement.remove();
            delete _tags[this.parentElement._name];
            _form["etiqueta"].disabled = false;
        };
        return etiqueta;
    }

    /**
     *
     * @private
     */
    function _fetchTags() {
        fetch(`/api/tags`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _this.setClassState("css-loaded");
                    _processTags(response.data);
                } else {
                    errorAlert(response.error.message.toString());
                    _this.setClassState("css-error");
                }
            })
            .catch((reason) => {
                errorAlert(reason.toString());
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processTags(data) {
        _dataListTags.innerHTML = "";
        data.tags.forEach((tag) => {
            _dataListTags.append(_createOption(tag.nombre, tag.nombre));
        });
    }

    /**
     *
     * @private
     */
    function _onKeyDownEtiquetas(event) {
        if (event.keyCode !== 13) {
            return;
        } else if (Object.values(_tags).length >= 10) {
            this.disabled = true;
            warningAlert("Maximo de etiqueta alcanzado");
            this.value = "";
            return;
        }

        event.preventDefault();

        this.value = this.value.toLowerCase();

        if (this.value.length >= 25) {
            warningAlert("La etiqueta es demasido larga");
            return;
        }

        if (_tags[this.value] === undefined && this.value != "") {
            _tags[this.value] = true;
            _tagZone.append(_createEtiqueta(this.value));
            this.value = "";
        } else {
            this.value = "";
        }
    }

    /**
     *
     * @private
     */
    function _onChangeEmisores() {
        if (this.value !== "-1") {
            _form["nuevoEmisor"].parentElement.classList.add("d-none");
            _form["nuevoEmisor"].disabled = true;
            _form["nuevoEmisor"].required = false;
            return;
        }
        _form["nuevoEmisor"].disabled = false;
        _form["nuevoEmisor"].required = true;
        _form["nuevoEmisor"].parentElement.classList.remove("d-none");
    }

    /**
     *
     * @param value
     * @param text
     * @returns {HTMLOptionElement}
     * @private
     */
    function _createOption(value, text) {
        const option = document.createElement("option");
        option.value = value;
        option.label = text;
        option.innerText = text;
        return option;
    }

    /**
     *
     * @private
     */
    function _fetchEmisores() {
        fetch(`/api/emisores`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processEmisores(response.data);
                } else {
                    errorAlert(response.error.message.toString());
                }
            })
            .catch((reason) => {
                errorAlert(reason.toString());
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processEmisores(data) {
        _form["emisor"].innerHTML = "";
        _form["emisor"].append(_createOption(0, "Seleccionar emisor"));
        data.emisores.forEach((emisor) => {
            _form["emisor"].append(_createOption(emisor.nombre, emisor.nombre));
        });
        _form["emisor"].append(_createOption(-1, "Nuevo emisor"));
        if (_emisor != "") {
            _form["emisor"].value = _emisor;
        }
    }

    /**
     *
     * @private
     */
    function _onChangeTipos() {
        if (this.value !== "-1") {
            _form["nuevoTipo"].parentElement.classList.add("d-none");
            _form["nuevoTipo"].disabled = true;
            _form["nuevoTipo"].required = false;
            return;
        }
        _form["nuevoTipo"].disabled = false;
        _form["nuevoTipo"].required = true;
        _form["nuevoTipo"].parentElement.classList.remove("d-none");
    }

    /**
     *
     * @private
     */
    function _fetchTipos() {
        fetch(`/api/tipos`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processTipos(response.data);
                } else {
                    _this.setClassState("css-error");
                    errorAlert(response.error.message.toString());
                }
            })
            .catch((reason) => {
                errorAlert(reason.toString());
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processTipos(data) {
        _form["tipo"].innerHTML = "";
        _form["tipo"].append(_createOption(0, "Seleccionar tipo"));
        data.tipos.forEach((tipo) => {
            _form["tipo"].append(_createOption(tipo.nombre, tipo.nombre));
        });
        _form["tipo"].append(_createOption(-1, "Nuevo tipo"));
        if (_tipo != "") {
            _form["tipo"].value = _tipo;
        }
    }

    /**
     *
     * @private
     */
    function _onChangeDerogar(event) {
        if (event.target.value == 1) {
            _documentoDerogadorForm.classList.add("d-none");
            _documentoDerogadorForm.disabled = true;
            _documentoDerogadorForm.required = false;
        } else if (event.target.value == 0) {
            _documentoDerogadorForm.classList.remove("d-none");
            _documentoDerogadorForm.disabled = false;
            _documentoDerogadorForm.required = true;
        }
    }

    /**
     *
     * @private
     */
    function _fetchDocumentos() {
        fetch(`/api/documentos?visible=`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _processDocumentos(response.data);
                } else {
                    _this.setClassState("css-error");
                    errorAlert(response.error.message.toString());
                }
            })
            .catch((reason) => {
                errorAlert(reason.toString());
            });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processDocumentos(data) {
        const _documentoDerogadorList = _this.root.querySelector(
            '[data-js="documentoDerogadorList"]'
        );
        _documentoDerogadorList.innerHTML = "";
        data.documentos.forEach((documento) => {
            if (documento.id != _id) {
                _documentoDerogadorList.append(
                    _createOption(
                            documento.numeroExpediente,
                            "Numero: " +
                            documento.numeroExpediente+
                            " Fehca: " +
                            documento.fechaEmision
                    )
                );
                if( documento.id == _documentoDerogador){
                    _form["documentoDerogador"].value = documento.numeroExpediente;
                }
            }
        });
    }

    /**
     *
     * @param event
     * @private
     */
    function _onSubmit(event) {
        _form["submitBtn"].disabled = true;
        _form["submitBtn"].lastElementChild.classList.remove("d-none");
        const formData = new FormData();

        if (_form["titulo"].value.length >= 45) {
            warningAlert("El titulo es demasido largo");
            return false;
        }
        formData.append("titulo", _form["titulo"].value);

        if (_form["numeroExpediente"].value.length >= 45) {
            warningAlert("El número es demasido largo");
            return false;
        }
        formData.append("numeroExpediente", _form["numeroExpediente"].value);

        formData.append("descripcion", _form["descripcion"].value);
        formData.append("fechaEmision", _form["fechaEmision"].value);
        formData.append(
            "descargable",
            _form["descargable"].value == 1 ? true : false
        );
        formData.append("publico", _form["publico"].value == 1 ? true : false);

        if (_form["emisor"].value === "-1") {
            formData.append("emisor", _form["nuevoEmisor"].value);
        } else if (_form["emisor"].value === "0") {
            warningAlert("Debe seleccionar un emisor valido");
            _form["submitBtn"].disabled = false;
            _form["submitBtn"].lastElementChild.classList.add("d-none");
            return false;
        } else {
            if (_form["emisor"].value.length >= 25) {
                warningAlert("El emisor es demasido largo");
                return false;
            }
            formData.append("emisor", _form["emisor"].value);
        }

        if (_form["tipo"].value === "-1") {
            formData.append("tipo", _form["nuevoTipo"].value);
        } else if (_form["tipo"].value === "0") {
            warningAlert("Debe seleccionar un tipo");
            _form["submitBtn"].disabled = false;
            _form["submitBtn"].lastElementChild.classList.add("d-none");
            return false;
        } else {
            if (_form["tipo"].value.length >= 25) {
                warningAlert("El nombre del tipo es demasido largo");
                return false;
            }
            formData.append("tipo", _form["tipo"].value);
        }

        
        if (_form["derogar"].value == 0) {
            if( _form["documentoDerogador"].value == ''){
                warningAlert("Seleccionar un documento para derogar");
                return false;
            }
            formData.append("derogado", true);
            formData.append("derogadoId", _form["documentoDerogador"].value);
        } else {
            formData.append("derogado", false);
            formData.append("derogadoId", null);
        }

        if (Object.keys(_tags).length === 0) {
            warningAlert("Debe agregar etiquetas");
            _form["submitBtn"].disabled = false;
            _form["submitBtn"].lastElementChild.classList.add("d-none");
            return false;
        } else {
            formData.append("tags", JSON.stringify(Object.keys(_tags)));
        }

        if (!_file === null) {
            formData.append("documento_pdf", _file);
        }

        const url = window.location.pathname;
        const id = url.substring(url.lastIndexOf("/") + 1);
        fetch(`/api/documentos/${id}`, { method: "POST", body: formData })
            .then((httpResp) => httpResp.json())
            .then((response) => {
                _form["submitBtn"].disabled = false;
                _form["submitBtn"].lastElementChild.classList.add("d-none");
                if (response.code === 200) {
                    successAlert("El documento se actualizo con exito");
                    _fetchTags();
                    _fetchEmisores();
                } else {
                    errorAlert(response.error.message.toString());
                }
            })
            .catch((reason) => {
                _form["submitBtn"].disabled = false;
                _form["submitBtn"].lastElementChild.classList.add("d-none");
                errorAlert(reason.toString());
            });
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(AdminEditDocumento.prototype, new Component());
