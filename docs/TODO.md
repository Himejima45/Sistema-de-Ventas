13 7
75 26


MODULO IA --> Catalogo = manejar 4 marcas y 4 modelos por marca;
1 automatica y 1 sincronica .;
haces commit y subir a github ;

Apartado ventas : ;
//agregar
1: metodo de pago por denominaciones , metodo manual;
2: Registrar cliente al momento de facturar.Apartado;
3: rutas en los apartdos para saber donde esta ubicado el usuario;

Que lleva el registro de usuario:
 1: Nombre
 2: Apellido
 3: Cedula
 4: Nro. Telefono
 5: Direccion 

# TODO - Correcciones
4. Modulo cierre
- Añadir salida pdf y excel
- El cierre debe contener VentaId | Usuario | Hora y fecha

# Todo List
2.  Usar local no online
4.  Añadir client_id a sales
5.  Implementar factura
6.  Usar user en vez de client
7.  Remover el dia extra o validar que en la db se guarde la hora correctamente (chequear tz)
8.  Implementar factura
10. Añadir traducción por cada regla
11. No tiene sentido ese codigo

# Notas
-   Traducir
    -   Mensajes de error
    -   Mensaje de inicio de sesión
-   Mostrar el nombre de la vista en el título de la página
    -   Ej
        -   SISTEMA DE VENTAS / Usuarios
        -   SISTEMA DE VENTAS / Roles
-   Sidebar
    -   Items sin links
        -   Asignar
-   Validaciones
    -   CI, tlf, correo (unicos, comparar en db)
-   Imagenes (Guardar correctamente y traer)

# WIP
-   Limpiar codigo sin uso
-   Renombrar sales por order
-   Validaciones

