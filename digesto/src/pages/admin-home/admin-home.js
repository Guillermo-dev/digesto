import {AdminMenu} from "../../components/AdminMenu.js";
import AdminDocumentos from "../../components/AdminDocumentos.js";
import AdminSearch from "../../components/AdminSearch.js";
import {Daijesuto} from "../../components/Daijesuto.js";

const adminSearch = new AdminSearch();

const adminDocumentos = new AdminDocumentos();

const adminMenu = new AdminMenu();

const daijesuto = new Daijesuto();

adminSearch.setDocumentosComponent(adminDocumentos);

adminSearch.append('AdminSearch');
adminDocumentos.append('AdminDocumentos');
adminMenu.append('AdminMenu');