import {
    createElement,
    createStyle,
    errorAlert,
    successAlert,
    warningAlert,
} from "../global/js/util.js";
import { Component } from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminNewDocumento:not(.css-loaded) .css-loaded,
    .AdminNewDocumento:not(.css-loading) .css-loading,
    .AdminNewDocumento:not(.css-error) .css-error {
        display: none !important;
    }

    .AdminNewDocumento .drag-zone {
        border: dashed #d6d7db 1px;
        background-color: #f1f1f5;
        color: #60636d;
        min-height: 150px;
        transition: border .3s ease-in-out;
    }

    .AdminNewDocumento .drag-zone.changed {
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
export default function AdminNewDocumento() {
    this.root = createElement("div")._class("AdminNewDocumento")
        ._html(`<!--Loaded-->
<div class="container css-loaded">
    <div class="cuerpoDoc mt-4 border shadow mb-5 bg-white  rounded-top" id="cuerpoDoc">
        <div class="encabezadoDoc text-center" id="encabezadoDoc">
            <h3>Crear documento</h3>
        </div>
        <form class="p-4" data-js="form">
            <div class="row g-3 mb-3">
                <div class="col-sm">
                    <label class="fw-bold mt-2" for="titulo">Título<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="titulo" id="titulo " placeholder="" autocomplete="off">
                </div>
                <div class="col-sm">
                    <label class="fw-bold mt-2" for="numero">Número expediente<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="numeroExpediente" id="numero" placeholder="" autocomplete="off">
                </div>
            </div>
            <div class="mb-3">
                <label class="fw-bold mt-2" for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Pequeño resumen del contenido del documento (opcional)"></textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm">
                    <label class="fw-bold mt-2" for="campaña">Fecha de emisión<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaEmision" id="fecha " placeholder="">
                </div>
                <div class="col-sm">
                    <label class="fw-bold mt-2" for="campaña">Tipo<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="tipo" required id="tipo " placeholder="Resolucion, Normativa, etc" autocomplete="off">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm">
                    <label class="fw-bold mt-2" for="campaña">Emisor<span class="text-danger">*</span></label>
                    <select class="form-control" name="emisor">
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
                <label class="fw-bold mt-2" for="campaña">Etiquetas<span class="text-danger">*</span></label>
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
                            <input class="form-check-input" type="radio" name="publico" id="Privado" value="0">
                            <label class="form-check-label" for="Privado">Privado</label>
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
            <div class="d-none" data-js="archivo"> 
                <iframe data-js="NoTieneQueSerNecesariamenteLoQueQuieras" src="" class="frame" frameborder="5" width="100%" height="680px" ></iframe>
            </div>
            <p class="fw-bold text-danger mb-3">*Campos obligatorios</p>
            <div class="text-end">
                <button type="button" class="btn btn-primary" data-js="button">Cancelar</button>
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
    this.name = "AdminNewDocumento";

    const _this = this;
    const _tagZone = _this.root.querySelector('[data-js="tag-zone"]');
    const _dataListTags = _this.root.querySelector('[data-js="datalist-tags"]');
    const _dragZone = _this.root.querySelector('[data-js="drag-zone"]');
    const _fileText = _this.root.querySelector('[data-js="file-text"]');
    const _form = _this.root.querySelector('[data-js="form"]');
    const _archivoContainer = _this.root.querySelector('[data-js="archivo"]');
    const _pdf = _this.root.querySelector(
        '[data-js="NoTieneQueSerNecesariamenteLoQueQuieras"]'
    );
    const _exitButton = _this.root.querySelector('[data-js="button"]');

    let _file = null;
    let _tags = {};
    let _createTag = false;
    let _tagsData = {};

    /**
     * Constructor
     * @private
     */
    function _constructor() {
        _this.setClassState("css-loading");
        _fetchEmisores();
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
     * @param file
     * @private
     */
    function _onSelectFile(file) {
        if (file.type !== "application/pdf") {
            errorAlert("El archivo debe ser de tipo PDF");
            return;
        }

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
        _archivoContainer.classList.add("d-none");
        _form["file"].value = "";
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
                    _processTags(response.data);
                    _this.setClassState("css-loaded");
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
    function _processTags(data) {
        _dataListTags.innerHTML = "";
        data.tags.forEach((tag) => {
            _dataListTags.append(_createOption(tag.nombre, tag.nombre));
            _tagsData[tag.nombre] = true;
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
            warningAlert("Etiqueta demasido larga");
            return;
        }

        if (_tags[this.value] === undefined && this.value != "") {
            _tags[this.value] = true;
            _tagZone.append(_createEtiqueta(this.value));
            this.value = "";
        } else {
            this.value = "";

        }
        _createTag = false;
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
    function _processEmisores(data) {
        _form["emisor"].innerHTML = "";
        _form["emisor"].append(_createOption(0, "Seleccionar emisor"));
        data.emisores.forEach((emisor) => {
            _form["emisor"].append(_createOption(emisor.nombre, emisor.nombre));
        });
        _form["emisor"].append(_createOption(-1, "Nuevo emisor"));
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

        if (_form["numeroExpediente"].value.length >= 25) {
            warningAlert("El numero de expediente es demasido largo");
            return false;
        }
        formData.append("numeroExpediente", _form["numeroExpediente"].value);

        formData.append("descripcion", _form["descripcion"].value);
        formData.append("fechaEmision", _form["fechaEmision"].value);

        if (_form["tipo"].value.length >= 25) {
            warningAlert("El tipo es demasido largo");
            return false;
        }
        formData.append("tipo", _form["tipo"].value);
        formData.append("descargable", _form["descargable"].value);
        formData.append("publico", _form["publico"].value);

        if (_form["emisor"].value === "-1") {
            formData.append("emisor", _form["nuevoEmisor"].value);
        } else if (_form["emisor"].value === "0") {
            warningAlert("Debe seleccionar un emisor");
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

        if (Object.keys(_tags).length === 0) {
            warningAlert("Debe agregar etiquetas");
            _form["submitBtn"].disabled = false;
            _form["submitBtn"].lastElementChild.classList.add("d-none");
            return false;
        } else {
            formData.append("tags", JSON.stringify(Object.keys(_tags)));
        }

        if (_file === null) {
            warningAlert("Debe seleccionar un archivo");
            _form["submitBtn"].disabled = false;
            _form["submitBtn"].lastElementChild.classList.add("d-none");
            return false;
        } else {
            formData.append("documento_pdf", _file);
        }

        fetch(`/api/documentos`, { method: "POST", body: formData })
            .then((httpResp) => httpResp.json())
            .then((response) => {
                _form["submitBtn"].disabled = false;
                _form["submitBtn"].lastElementChild.classList.add("d-none");
                if (response.code === 200) {
                    successAlert("El documento se registro con exito");
                    _clear();
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

    /**
     *
     * @private
     */
    function _clear() {
        _file = null;
        _fileText.classList.add("d-none");
        _form["file"].value = "";
        _form.reset();
        _archivoContainer.classList.add("d-none");
        _tags = {};
        _form["nuevoEmisor"].parentElement.classList.add("d-none");
        _form["nuevoEmisor"].disabled = true;
        _form["nuevoEmisor"].required = false;
        _tagZone.innerHTML = "";
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(AdminNewDocumento.prototype, new Component());
