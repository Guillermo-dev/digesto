import DocumentList from "../../components/DocumentList.js";
import Search from "../../components/Search.js";

//Create Search component
const search = new Search();
const documentList = new DocumentList();

//Append component to the page
search.append('Search');
documentList.append('DocumentList');