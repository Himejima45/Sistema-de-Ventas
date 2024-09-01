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

# Todo List
1.  Arreglar campos en usuario
    -   profile (Rol) y status (activo/inactivo?) principalmente
2.  Usar local no online
3.  Validar que la contraseña sea la misma que la ingresada al registrar
    -   Añadir:
        -   Contraseña actual, nueva contraseña y confirmar contraseña
4.  Añadir client_id a sales
5.  Implementar factura
6.  Usar user en vez de client
7.  Remover el dia extra o validar que en la db se guarde la hora correctamente (chequear tz)
8.  Implementar factura
9.  Usar client en vez de user ?

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
-   Imagenes