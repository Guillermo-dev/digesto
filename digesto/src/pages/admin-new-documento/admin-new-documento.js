import AdminNewDocumento from "../../components/AdminNewDocumento.js";
import { Daijesuto } from "../../components/Daijesuto.js";
import {AdminMenu} from "../../components/AdminMenu.js";

const daijesuto = new Daijesuto();

const adminNewDocumento = new AdminNewDocumento();

const adminMenu = new AdminMenu();

adminNewDocumento.append('AdminNewDocumento');
adminMenu.append('AdminMenu');