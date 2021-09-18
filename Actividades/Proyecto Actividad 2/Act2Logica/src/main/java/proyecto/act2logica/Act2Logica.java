package proyecto.act2logica;

import java.util.ArrayList;
import java.util.Scanner;
import proyecto.act2modelo.Usuario;
import proyecto.act2modelo.Documento;
import proyecto.act2modelo.Etiqueta;

public class Act2Logica {

    public static Scanner in = new Scanner(System.in);

    public static void menu() {
        System.out.println("Seleccionar opcion:");
        System.out.println("======= USUARIO ======= \n"
                + "1) Crear usuario \n"
                + "2) Modificar usuario \n"
                + "3) Listar usuarios \n"
                + "4) Eliminar usuario\n "
                + "\n ======= DOCUMENTOS ======= \n"
                + "5) Crear documento \n"
                + "6) Modificar documento \n"
                + "7) Listar documento \n"
                + "8) Eliminar documento\n "
                + "\n ======= ETIQUETAS ======= \n "
                + "9) Crear etiqueta \n"
                + "10) Modificar etiqueta \n"
                + "11) Listar etiqueta \n"
                + "12) Eliminar etiqueta \n"
                + "==============\n"
                + "13)SALIR");
    }

    public static void crearUsuario(ArrayList<Usuario> usuarios) {
        System.out.println("======= CREAR USUARIO =======");
        System.out.println("Ingresar nombre: ");
        String nombre = in.nextLine();
        System.out.println("Ingrese apellido: ");
        String apellido = in.nextLine();
        System.out.println("Ingrese email:");
        String email = in.nextLine();
        System.out.println("Ingrese cargo:");
        String cargo = in.nextLine();
        Usuario usuario = new Usuario(nombre, apellido, email, cargo);

        usuarios.add(usuario);
        System.out.println("USUARIO CREADO CON EXITO :D");
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static Usuario buscarUsuario(ArrayList<Usuario> usuarios, String email) {
        for (Usuario usuario : usuarios) {
            if (usuario.getEmail().equals(email)) {
                return usuario;
            }
        }
        return null;
    }

    public static void modificarUsuario(ArrayList<Usuario> usuarios) {
        System.out.println("======= MODIFICAR USUARIO =======");
        System.out.println("Ingrese email del usuario a modificar:");
        String emailABuscar = in.nextLine();
        Usuario usuario = buscarUsuario(usuarios, emailABuscar);
        if (usuario != null) {
            System.out.println("Datos del usuario:");
            System.out.println("Nombre: " + usuario.getNombre() + "\n"
                    + "Apellido: " + usuario.getApellido() + " \n"
                    + "Email: " + usuario.getEmail() + " \n"
                    + "Cargo; " + usuario.getCargo());
            System.out.println("Ingrese nuevo nombre: ");
            String nombre = in.nextLine();
            System.out.println("Ingrese nuevo apellido: ");
            String apellido = in.nextLine();
            System.out.println("Ingrese nuevo email: ");
            String email = in.nextLine();
            System.out.println("Ingrese nuevo cargo: ");
            String cargo = in.nextLine();

            usuario.setNombre(nombre);
            usuario.setApellido(apellido);
            usuario.setEmail(email);
            usuario.setCargo(cargo);

            System.out.println("USUARIO MODIFICADO CON EXITO :D");
        } else {
            System.out.println("El usuario no existe :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void listarUsuarios(ArrayList<Usuario> usuarios) {
        usuarios.forEach((usuario) -> {
            System.out.println("Nombre: " + usuario.getNombre() + "\n"
                    + "Apellido: " + usuario.getApellido() + " \n"
                            + "Email: " + usuario.getEmail() + " \n"
                                    + "Cargo; " + usuario.getCargo() + "\n"
                                            + "=============================");
        });
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void eliminarUsuario(ArrayList<Usuario> usuarios) {
        System.out.println("======= ELIMINAR USUARIO =======");
        System.out.println("Ingrese email del usuario a eliminar:");
        String emailABuscar = in.nextLine();
        Usuario usuario = buscarUsuario(usuarios, emailABuscar);
        if (usuario != null) {
            usuarios.remove(usuario);
            System.out.println("Usuario eliminado con exito :D");
        } else {
            System.out.println("Usuario no encontrado :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void crearDocumento(ArrayList<Documento> documentos) {
        System.out.println("======= CREAR DOCUMENTO =======");
        System.out.println("Ingresar nombre: ");
        String nombre = in.nextLine();
        System.out.println("Ingrese descripcion: ");
        String descripcion = in.nextLine();
        System.out.println("Ingrese fecha:");
        String fecha = in.nextLine();
        System.out.println("Ingrese emisor:");
        String emisor = in.nextLine();
        Documento documento = new Documento(nombre, descripcion, fecha, emisor);

        documentos.add(documento);
        System.out.println("DOCUMENTO CREADO CON EXITO");
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static Documento buscarDoc(ArrayList<Documento> documentos, String nombre) {
        for (Documento doc : documentos) {
            if (doc.getNombre().equals(nombre)) {
                return doc;
            }
        }
        return null;
    }

    public static void modificarDocumento(ArrayList<Documento> documentos) {
        System.out.println("======= MODIFICAR DOCUMENTO =======");
        System.out.println("Ingrese nombre del documento a modificar:");
        String nombreABuscar = in.nextLine();
        Documento documento = buscarDoc(documentos, nombreABuscar);
        if (documento != null) {
            System.out.println("Datos del documento:");
            System.out.println("Nombre: " + documento.getNombre() + "\n"
                    + "Descripcion: " + documento.getDescripcion() + " \n"
                    + "fecha: " + documento.getFecha() + " \n"
                    + "emisor; " + documento.getEmisor());
            System.out.println("Ingrese nuevo nombre: ");
            String nombre = in.nextLine();
            System.out.println("Ingrese nueva descripcion: ");
            String descripcion = in.nextLine();
            System.out.println("Ingrese nueva fecha: ");
            String fecha = in.nextLine();
            System.out.println("Ingrese nuevo emisor: ");
            String emisor = in.nextLine();

            documento.setNombre(nombre);
            documento.setDescripcion(descripcion);
            documento.setFecha(fecha);
            documento.setEmisor(emisor);

            System.out.println("DOCUMENTO MODIFICADO CON EXITO :D");
        } else {
            System.out.println("El documento no existe :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void listarDocumentos(ArrayList<Documento> documentos) {
        documentos.forEach((documento) -> {
            System.out.println("Nombre: " + documento.getNombre() + "\n"
                    + "Descripcion: " + documento.getDescripcion() + " \n"
                            + "fecha: " + documento.getFecha() + " \n"
                                    + "emisor; " + documento.getEmisor()
                    + "\n =============================");
        });
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void eliminarDocumento(ArrayList<Documento> documentos) {
        System.out.println("======= ELIMINAR DOCUMENTO =======");
        System.out.println("Ingrese nombre del documento a eliminar:");
        String nombreABuscar = in.nextLine();
        Documento documento = buscarDoc(documentos, nombreABuscar);
        if (documento != null) {
            documentos.remove(documento);
            System.out.println("Documento eliminado con exito :D");
        } else {
            System.out.println("Documento no encontrado :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void crearEtiqueta(ArrayList<Etiqueta> etiquetas) {
        System.out.println("======= CREAR ETIQUETA =======");
        System.out.println("Ingresar nombre: ");
        String nombre = in.nextLine();
        System.out.println("Ingrese descripcion: ");
        String descripcion = in.nextLine();

        Etiqueta etiqueta = new Etiqueta(nombre, descripcion);

        etiquetas.add(etiqueta);
        System.out.println("ETIQUETA CREADA CON EXITO");
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static Etiqueta buscarEtiqueta(ArrayList<Etiqueta> etiquetas, String nombre) {
        for (Etiqueta etiqueta : etiquetas) {
            if (etiqueta.getNombre().equals(nombre)) {
                return etiqueta;
            }
        }
        return null;
    }

    public static void modificarEtiqueta(ArrayList<Etiqueta> etiquetas) {
        System.out.println("======= MODIFICAR ETIQUETA =======");
        System.out.println("Ingrese nombre del etiqueta a modificar:");
        String nombreABuscar = in.nextLine();
        Etiqueta etiqueta = buscarEtiqueta(etiquetas, nombreABuscar);
        if (etiqueta != null) {
            System.out.println("Datos de la etiqueta:");
            System.out.println("Nombre: " + etiqueta.getNombre() + "\n"
                    + "Descripcion: " + etiqueta.getDescripcion());
            System.out.println("Ingrese nuevo nombre: ");
            String nombre = in.nextLine();
            System.out.println("Ingrese nueva descripcion: ");
            String descripcion = in.nextLine();

            etiqueta.setNombre(nombre);
            etiqueta.setDescripcion(descripcion);

            System.out.println("ETIQUETA MODIFICADA CON EXITO :D");
        } else {
            System.out.println("La etiqueta no existe :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void listarEtiquetas(ArrayList<Etiqueta> etiquetas) {
        etiquetas.forEach((etiqueta) -> {
            System.out.println("Nombre: " + etiqueta.getNombre() + "\n"
                    + "Descripcion: " + etiqueta.getDescripcion() + " \n"
                            + "\n =============================");
        });
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static void eliminarEtiqueta(ArrayList<Etiqueta> etiquetas) {
        System.out.println("======= ELIMINAR ETIQUETA =======");
        System.out.println("Ingrese nombre de la etiqueta a eliminar:");
        String nombreABuscar = in.nextLine();
        Etiqueta etiqueta = buscarEtiqueta(etiquetas, nombreABuscar);
        if (etiqueta != null) {
            etiquetas.remove(etiqueta);
            System.out.println("Etiqueta eliminada con exito :D");
        } else {
            System.out.println("etiqueta no encontrada :c");
        }
        System.out.println("[ENTER] para continuar");
        in.nextLine();
    }

    public static boolean opcionValida(int opcion) {
        return (opcion > 0 && opcion < 13 || opcion == -1);
    }

    public static void seleccionarOperacion(ArrayList<Usuario> usuarios,
            ArrayList<Documento> documentos, ArrayList<Etiqueta> etiquetas) {
        int opcion;
        do {
            menu();
            try {
                opcion = in.nextInt();
                in.nextLine();
            } catch (Exception e) {
                opcion = -1;
                in.nextLine();
            }

            if (opcionValida(opcion)) {
                switch (opcion) {
                    case 1:
                        crearUsuario(usuarios);
                        break;
                    case 2:
                        modificarUsuario(usuarios);
                        break;
                    case 3:
                        listarUsuarios(usuarios);
                        break;
                    case 4:
                        eliminarUsuario(usuarios);
                        break;
                    case 5:
                        crearDocumento(documentos);
                        break;
                    case 6:
                        modificarDocumento(documentos);
                        break;
                    case 7:
                        listarDocumentos(documentos);
                        break;
                    case 8:
                        eliminarDocumento(documentos);
                        break;
                    case 9:
                        crearEtiqueta(etiquetas);
                        break;
                    case 10:
                        modificarEtiqueta(etiquetas);
                        break;
                    case 11:
                        listarEtiquetas(etiquetas);
                        break;
                    case 12:
                        eliminarEtiqueta(etiquetas);
                        break;
                    default:
                        break;
                }
            }

        } while (opcionValida(opcion));
    }

    public static void main(String[] args) {
        ArrayList<Usuario> usuarios = new ArrayList<>();
        ArrayList<Documento> documentos = new ArrayList<>();
        ArrayList<Etiqueta> etiquetas = new ArrayList<>();
        seleccionarOperacion(usuarios, documentos, etiquetas);
    }

}
