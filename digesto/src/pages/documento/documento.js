import DocumentoAmpliado from "../../components/DocumentoAmpliado.js";
import Search from "../../components/Search.js";

//Create Search component
const search = new Search();

//Create DocumentoAmpliado component
const documentoAmpliado = new DocumentoAmpliado();

//Append components to the page
search.append('Search');
documentoAmpliado.append('DocumentoAmpliado');