import Documentos from "../../components/Documentos.js";
import Search from "../../components/Search.js";
import {Daijesuto} from "../../components/Daijesuto.js";

const search = new Search();

const documentos = new Documentos();

const daijesuto = new Daijesuto();

search.setDocumentosComponent(documentos);

search.append('Search');
documentos.append('Documentos');