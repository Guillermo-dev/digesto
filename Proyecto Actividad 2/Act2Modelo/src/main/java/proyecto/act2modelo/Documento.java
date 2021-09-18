package proyecto.act2modelo;

public class Documento {
    private String nombre;
    private String descripcion;
    private String fecha;
    private String emisor;

    public Documento(String nombre, String descripcion, String fecha, String emisor) {
        this.nombre = nombre;
        this.descripcion = descripcion;
        this.fecha = fecha;
        this.emisor = emisor;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public String getFecha() {
        return fecha;
    }

    public void setFecha(String fecha) {
        this.fecha = fecha;
    }

    public String getEmisor() {
        return emisor;
    }

    public void setEmisor(String emisor) {
        this.emisor = emisor;
    }
    
    
}
