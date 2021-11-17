import AdminNewDocumento from "../../components/AdminNewDocumento.js";
import { Daijesuto } from "../../components/Daijesuto.js";
import AdminSearch from "../../components/AdminSearch.js";

const daijesuto = new Daijesuto();

const adminNewDocumento = new AdminNewDocumento();

const adminSearch = new AdminSearch();

adminSearch.append('AdminSearch');
adminNewDocumento.append('AdminNewDocumento');