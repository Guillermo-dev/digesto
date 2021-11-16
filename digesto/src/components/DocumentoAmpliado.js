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
    width: 120px;
}

.vistaDoc {
    background-color: rgb(240, 240, 240);
    padding: 10px 10px 10px 10px;
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
                    _processDocumento(
                        response.data["documento"],
                        response.data["emisor"],
                        response.data["tags"]
                    );
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

    function _processDocumento(documento, emisor, tags) {
        console.log(tags);
        _content.append(
            (_this.root = createElement("div")._class("UsuarioEntry")._html(`
        <div class="cuerpoDoc mt-4 border shadow mb-5 bg-white  rounded-top" id="cuerpoDoc">
            <div class="encabezadoDoc " id="encabezadoDoc">
                <h3> Detalles del Documento</h3>
            </div>
            <div class="informacionDoc" id="informacionDoc">
                <div class="pContenedor">
                    <p>Número:</p>
                    <p data-js="infoBD">${documento.numeroExpediente}</p>
                </div>
                <br>
                <div class="pContenedor">
                    <p>Título:</p>
                    <p data-js="infoBD">${documento.titulo}</p>
                </div>
                <br>
                <div class="pContenedor">
                    <p>Fecha:</p>
                    <p data-js="infoBD">${documento.fechaEmision}</p>
                </div>
                <br>
                <div class="pContenedor">
                    <p>Descripción:</p>
                    <p data-js="infoBD">${documento.descripcion}</p>
                </div>
                <br>
                <div class="pContenedor">
                    <p>Emisor: </p>
                    <p data-js="infoBD">${emisor.nombre}</p>
                </div>
                <br>
                <div class="pContenedor">
                    <p>Etiquetas:</p>
                    <div data-js="tags">
                        <!-- tags -->
                    </div>
                </div>
            </div>
            ${
                documento.descargable
                    ? ` 
                <div class="vistaDoc " id="vistaDoc ">
                    <iframe src="../../global/images/AleBombones.pdf" class="frame" frameborder="5" width="100%" height="680px" > </iframe>
                </div> `
                    : ` DOCUMENTO NO DISPONIBLE`
            }
        </div>
        `))
        );
        const _tagsList = _this.root.querySelector('[data-js="tags"]');
        tags.forEach((tag) => {
            _tagsList.append(_createTagElement(tag.nombre));
        });
    }

    function _createTagElement(nombre) {
        const element = createElement("div")._html(
            `<p data-js="infoBD">${nombre}</p>` // TODO: HACER CSS PAO DE TAGS
        );
        return element;
    }

    //armar el diseño de las tags
    // armar el js acá

    /* declaro una variable : const parrafos = _this.root.querySelector('[data-js="infoBD"]'); 
    (en la variable parrafos guarda el querySelector busca las etiquetas data-js = infoBD)
     */

    _constructor();
}

Object.setPrototypeOf(DocumentoAmpliado.prototype, new Component());
