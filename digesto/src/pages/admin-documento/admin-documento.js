import AdminEditDocumento from "../../components/AdminEditDocumento.js";
import { Daijesuto } from "../../components/Daijesuto.js";
import {AdminMenu} from "../../components/AdminMenu.js";

const daijesuto = new Daijesuto();

const adminEditDocumento = new AdminEditDocumento();

const adminMenu = new AdminMenu();

adminEditDocumento.append('AdminEditDocumento');
adminMenu.append('AdminMenu');