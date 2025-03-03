# Observaciones en proyecto "El Creador"
## Crear cliente:
1. [X]  Nombre de la persona física o moral
    1. Física: Solicitar por separado Nombres, apellido paterno y apellido materno
    2. Moral: Está bien como se marca (Todo junto)
2.  [X] El municipio y ciudad no esta correcto porque me arroja igual Tlahuac, pero en cdmx
hablamos de alcaldías y la ciudad seria cdmx pero no me deja anotar, lo correcto:
    1. Esto es por los valores en la base de datos, la CDMX está considerada como una "Entidad Federativa", las "Alcadías" como municipios


3.  [X] El código postal esta con los datos de celular , me gustaría que este del lado de los datos de
direccion por que ahi se pierde.
4.  [X] Con el código postal podemos agregar por automatico las colonias?
    * Ahora el código postal es requerido:
    * Si no existe borramos los datos de:
        * Entidad Federativa
        * Municipio (delegación)
        * Ciudad
        * Colonia
    * Si EXISTE asignamos valores a:
        * Entidad Federativa
        * Municipio (delegación)
        * Ciudad
        * Llenamos la lista de colonias  
---
## Cotizaciones
1. En el apartado de llenar cotizaciones al querer verla me aparece esto:

    ```
        Class "setassign\Fpdi" not found
        
        public function cotizacion($record)
        {
            $data = Cotization::findOrFail($record);
            $filePath = public_path('pdfs/cotizacion formal.pdf');
            $outputFilePath = public_path("output.pdf");
            $fpdi = new FPDI;
    ```

    En la línea: 
    ```
    $fpdi = new FPDI;
    ``
En el equipo "local"  no marca el error:

2. [X] La fecha probada no me queda muy claro ese apartado ademas de que aparece en ingles:
    * El botón de "Aprobada" es para indicar si la cotización ya fue aprobada o no
    * La fecha que está a un lado es para indicar que fecha se aprobó la cual debe ser igual o mayor a la fecha de la cotización.
    
## Ventas - Órdenes de compra:
1.  [X] Al crear una orden de compra no me da datos en automático debo de llenarlos de nuevo , se
supone que por eso creas al cliente para que cuando lo selecciones te aparezcan sus datos ya
en automático y no tengas que llenarlos de nuevo .

[X] Pendiente y trabajando en ello: En este momento al crear una orden de compra en ventas, al seleccionar el cliente traerá los datos de entrega, pero cabe la posibilidad de que no sea el mismo domicilio, por lo tanto el sistema debe permitir cambiarlo, esto lo haremos haciendo que cuando se introduzca el código postal este exista y traiga los datos. 

En esto es en lo que estaré trabajandoy les aviso cuando esté listo. 



