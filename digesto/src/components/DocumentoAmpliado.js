import { createElement, createStyle, errorAlert } from "../global/js/util.js";
import { Component } from "./Component.js";

createStyle("DocumentoAmpliadoEstilos")._content(`
.DocumentoAmpliado:not(.css-loaded) .css-loaded,
.DocumentoAmpliado:not(.css-loading) .css-loading,
.DocumentoAmpliado:not(.css-error) .css-error {
    display: none !important;
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
}

.pContenedor {
    display: flex;   
}

.pContenedor p:first-of-type {
    min-width: 120px;
}

.vistaDoc {
    background-color: rgb(240, 240, 240);
    padding: 10px 10px 10px 10px;
}
p {
    word-break: break-all;
}
 
`);

export default function DocumentoAmpliado() {
    const _this = this;
    this.name = "DocumentoAmpliado";
    this.root = createElement("div")._class("DocumentoAmpliado")._html(` 
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

    /**
     *
     * @constructor
     */
    function _constructor() {
        _fetchData();
    }

    function _fetchData() {
        _this.setClassState("css-loading");
        _content.innerHTML = "";
        const url = window.location.pathname;
        const id = url.substring(url.lastIndexOf("/") + 1);
        fetch(`/api/documentos/${id}`)
            .then((httpResp) => httpResp.json())
            .then((response) => {
                if (response.code === 200) {
                    _this.setClassState("css-loaded");
                    _processDocumento(response.data);
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

    function _processDocumento(data) {
        const documento = data["documento"];
        const emisor = data["emisor"];
        const tags = data["tags"];
        const pdf = data["pdf"];
        const tipo = data["tipo"];
        const derogado = data["derogado"]

        _content.append(
                (_this.root = createElement("div")._class("Documento")._html(`
        <div class="cuerpoDoc mt-4 border shadow mb-5 bg-white  rounded-top" id="cuerpoDoc">
            <div class="encabezadoDoc text-center " id="encabezadoDoc">
                <h3> Detalles del Documento</h3>
            </div>
            <div class="informacionDoc  border shadow " id="informacionDoc">
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Número:</p>
                    <p data-js="infoBD">${documento.numeroExpediente}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Título:</p>
                    <p data-js="infoBD">${documento.titulo}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Fecha:</p>
                    <p data-js="infoBD">${documento.fechaEmision}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Tipo:</p>
                    <p data-js="infoBD">${tipo.nombre}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Descripción:</p>
                    <p data-js="infoBD">${documento.descripcion}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Emisor: </p>
                    <p data-js="infoBD">${emisor.nombre}</p>
                </div>
                <div class="pContenedor border-bottom mb-3">
                    <p class=" fw-bold ">Etiquetas:</p>
                    <div class="mb-2 d-flex flex-wrap" data-js="tag-zone">
                        <!---->
                    </div>
                </div>
                ${derogado != undefined ? ` 
                <div class="pContenedor">
                    <p class=" fw-bold text-danger me-3">Este documento esta derogado por el documento: </p>
                    <a href="/documentos/${derogado.id}" class="text-dark">${derogado.titulo} - ${derogado.numeroExpediente}</a>
                </div>`:""
                }
            </div>
            <div class="vistaDoc " id="vistaDoc ">
                <iframe src= ${
                    documento.descargable
                        ? `../../../${pdf.path}`
                        : "../../../uploads/pdfNoDisponible.pdf"
                } class="frame" frameborder="5" width="100%" height="680px" > </iframe>
                <p class="mb-0">Para solicitar el documento original envie un email a <a href="mailto:unsada@unsada.edu.ar">unsada@unsada.edu.ar</a></p>
            </div> 
        </div>
        `))
        );
        const _tagZone = _this.root.querySelector('[data-js="tag-zone"]');
        tags.forEach((tag) => {
            _tagZone.append(_createTagElement(tag.nombre));
        });
    }

    function _createTagElement(nombre) {
        const element = createElement("div")._html(`            
        <span style="background-color: var(--bs-primary);" class="d-inline-block mb-2 p-2 text-white small rounded me-2">
            ${nombre}
        </span>` 
        );
        return element;
    }

    _constructor();
}

Object.setPrototypeOf(DocumentoAmpliado.prototype, new Component());
