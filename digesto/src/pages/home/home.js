import DocumentList from "../../components/DocumentList.js";
import Search from "../../components/Search.js";

//Create Search component
const search = new Search();

//Create DocumentoList component
const documentList = new DocumentList();

//Set a DocumentList reference into Search
search.setDocumentList(documentList);

//Append components to the page
search.append('Search');
documentList.append('DocumentList');