# Web Fichaje — Control horario de empleados con PHP y MySQL

Aplicación web de control horario desarrollada en **PHP**, con una base de datos MySQL/MariaDB y una interfaz basada en **HTML, CSS y Bootstrap 5**. Su objetivo es que los empleados registren su hora de entrada y salida y puedan consultar un resumen de las horas fichadas. Incluye también funciones administrativas para registrar usuarios y eliminar cuentas.

El proyecto utiliza una estructura sencilla inspirada en el patrón **Modelo–Vista–Controlador (MVC)**, sesiones PHP para mantener la identidad y el rol del usuario, y PDO para acceder a la base de datos.

> **Estado de instalación:** el código y el SQL incluidos en el ZIP no son plenamente compatibles entre sí. La aplicación espera una tabla `ficha` y campos de usuario como `nombre`, `apellidos` y `lvl`, pero el archivo `foro.sql` crea tablas `usuario`, `hilo` y `mensaje`, propias de una estructura de foro. Será necesario corregir el esquema de la base de datos antes de poder ejecutar todas las funciones correctamente.

## Índice

- [Funcionalidades](#funcionalidades)
- [Roles de usuario](#roles-de-usuario)
- [Tecnologías](#tecnologías)
- [Arquitectura](#arquitectura)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Modelo de datos esperado](#modelo-de-datos-esperado)
- [Funcionamiento del fichaje](#funcionamiento-del-fichaje)
- [Consulta de horas](#consulta-de-horas)
- [Rutas de la aplicación](#rutas-de-la-aplicación)
- [Instalación y configuración](#instalación-y-configuración)
- [Problemas conocidos y mejoras](#problemas-conocidos-y-mejoras)

## Funcionalidades

- **Inicio de sesión:** valida el usuario y la contraseña y guarda la identidad y el rol en la sesión.
- **Fichar entrada:** registra la hora en la que un empleado comienza su jornada.
- **Fichar salida:** registra la hora en la que termina la jornada.
- **Control de secuencia:** comprueba si existe una entrada previa y si hay una salida pendiente antes de permitir nuevos fichajes.
- **Límite temporal:** el código contiene una comprobación relacionada con un máximo de 22 horas desde la entrada antes de permitir la salida.
- **Consulta de fichajes:** muestra fechas de entrada y salida y calcula duraciones.
- **Resumen mensual:** agrupa los tiempos para mostrar totales por mes.
- **Administración de usuarios:** el rol administrador puede acceder a las opciones para registrar usuarios y borrar cuentas.
- **Cierre de sesión:** destruye la sesión y devuelve al usuario a la pantalla inicial de acceso.
- **Mensajes de resultado:** muestra avisos cuando un fichaje se registra correctamente o cuando se produce un error.
- **Interfaz responsive:** utiliza Bootstrap 5 para organizar los formularios, el menú y las tablas.

## Roles de usuario

El código usa la variable de sesión `$_SESSION['lvl']` para identificar el nivel de acceso.

| Nivel | Rol | Funciones previstas |
|---|---|---|
| `0` | Invitado | Acceder al formulario de login |
| `1` | Empleado | Fichar entrada y salida y consultar sus horas |
| `2` | Administrador | Funciones de empleado y acceso a registro y borrado de usuarios |

La plantilla `head.php` muestra diferentes opciones del menú según el nivel. El controlador también condiciona algunas acciones según el rol.

**Nota:** ocultar un enlace en el menú no constituye por sí solo una protección de seguridad. Cada acción debe verificar en el servidor que el usuario tiene permisos suficientes.

## Tecnologías

| Tecnología | Uso |
|---|---|
| PHP | Lógica de negocio, controladores, formularios y sesiones |
| PDO | Conexión y consultas a MySQL/MariaDB |
| MySQL / MariaDB | Persistencia de usuarios y registros de fichaje |
| HTML | Formularios, tablas y estructura de las páginas |
| CSS | Estilos propios de la aplicación |
| Bootstrap 5.0.2 | Diseño responsive y componentes visuales |
| Apache o servidor compatible con PHP | Ejecución de la aplicación web |

El código parece orientado a un entorno PHP tradicional y no utiliza un framework como Laravel o Symfony.

## Arquitectura

El proyecto separa el enrutamiento, el controlador, el acceso a datos y las plantillas, aunque parte de la lógica y de la presentación está concentrada en `Controlador.php`.

```text
Navegador
   |
   | Petición HTTP / formulario
   v
web/index.php
(entrada, sesión y enrutamiento)
   |
   v
app/Controlador.php
(login, permisos, fichajes y cálculos)
   |
   +--------------------------+
   |                          |
   v                          v
app/plantillas/           app/Conexion.php
(HTML y formularios)      (PDO y consultas SQL)
                              |
                              v
                         MySQL / MariaDB
                         Base de datos fichaje
```

### Responsabilidades

- **Punto de entrada (`web/index.php`):** carga el controlador, inicia la sesión, asigna el nivel de invitado si no existe y selecciona la acción que se debe ejecutar a partir del parámetro `dir`.
- **Controlador (`app/Controlador.php`):** implementa el login, el fichaje de entrada y salida, la consulta de horas, el alta y borrado de usuarios, y las redirecciones.
- **Conexión y modelo de datos (`app/Conexion.php`):** define la clase `Conectate`, que extiende PDO, mantiene una instancia compartida y centraliza las consultas SQL.
- **Configuración (`app/Config.php`):** define el host, la base de datos, el usuario y la contraseña.
- **Funciones auxiliares (`app/libs/bGeneral.php`):** recoge valores de formularios y ofrece validaciones de texto, correo, números, fechas, DNI/NIE y otros campos.
- **Utilidades (`app/libs/utils.php`):** contiene funciones auxiliares; actualmente incluye `timeOut()`, que espera ocupando el proceso.
- **Plantillas (`app/plantillas/`):** generan formularios y contenido HTML.
- **Recursos web (`web/`):** contiene el enrutador, hojas de estilo, imágenes y los archivos locales de Bootstrap.

## Estructura del proyecto

```text
web-fichaje-master/
├── LEEME.txt
├── foro.sql
├── app/
│   ├── Config.php
│   ├── Conexion.php
│   ├── Controlador.php
│   ├── libs/
│   │   ├── bGeneral.php
│   │   └── utils.php
│   └── plantillas/
│       ├── alta.php
│       ├── blanc.php
│       ├── borrarCuenta.php
│       ├── error.php
│       ├── exito.php
│       ├── head.php
│       ├── inicio.php
│       └── login.php
└── web/
    ├── index.php
    ├── style.css
    ├── nav.html
    ├── fields.html
    ├── bootstrap-5.0.2-dist/
    └── imagenes/
        ├── logo1.png
        ├── logo1tra.png
        ├── logo2.png
        └── fotosP/
```

### Archivos principales

- `web/index.php`: controla el acceso a las rutas y llama al método del controlador correspondiente.
- `app/Controlador.php`: contiene la mayor parte del comportamiento de la aplicación.
- `app/Conexion.php`: proporciona la conexión PDO y las operaciones de consulta, inserción y actualización de los fichajes.
- `app/Config.php`: contiene la configuración de conexión con la base de datos `fichaje`.
- `app/plantillas/inicio.php`: muestra los botones de entrada y salida.
- `app/plantillas/login.php`: formulario de acceso.
- `app/plantillas/alta.php`: formulario para registrar usuarios.
- `app/plantillas/borrarCuenta.php`: formulario para eliminar una cuenta por nombre de usuario, con casilla de confirmación.
- `app/plantillas/head.php`: cabecera común, logo, navegación y presentación del nombre y rol.
- `app/plantillas/blanc.php`: plantilla utilizada para presentar contenido, entre otros, el resumen de horas.
- `app/plantillas/exito.php` y `error.php`: páginas de resultado.
- `app/libs/bGeneral.php`: funciones para recoger y validar los datos de formularios.
- `foro.sql`: archivo SQL incluido. A pesar de su nombre, crea una base de datos llamada `fichaje`, pero sus tablas no corresponden al modelo que necesita la aplicación.

## Modelo de datos esperado

A partir de las consultas de `Conexion.php` y la lógica del controlador, la aplicación necesita al menos las siguientes estructuras.

### Tabla `usuario`

El código consulta estos campos:

| Campo | Uso |
|---|---|
| `id` | Identificador del usuario |
| `usuario` | Nombre de acceso |
| `contrasena` | Contraseña utilizada en el login |
| `nombre` | Nombre personal mostrado en la interfaz |
| `apellidos` | Apellidos mostrados en la interfaz |
| `mail` | Correo electrónico |
| `fotoP` | Ruta de la imagen de perfil |
| `fechaAlta` | Fecha de alta |
| `lvl` | Nivel de acceso: empleado o administrador |

Los campos se deducen de las consultas realizadas por el código; no todos están presentes en el SQL adjunto.

### Tabla `ficha`

Las operaciones de fichaje consultan y modifican una tabla llamada `ficha`.

| Campo | Uso esperado |
|---|---|
| `id` | Identificador único del registro |
| `id_user` | Usuario al que pertenece el fichaje |
| `SEntrada` | Marca temporal Unix de entrada |
| `SSalida` | Marca temporal Unix de salida; puede quedar sin valor hasta fichar la salida |
| `HEntrada` | Fecha/hora de entrada en formato de texto |
| `HSalida` | Fecha/hora de salida en formato de texto |

El código usa `SEntrada` y `SSalida` para calcular duraciones, y `HEntrada`/`HSalida` para almacenar una representación de fecha y hora. En la implementación actual, las marcas temporales parecen ser la fuente principal para calcular las horas.

### Esquema SQL incluido: incompatibilidad importante

El archivo `foro.sql` crea la base de datos `fichaje`, pero define las tablas `usuario`, `hilo` y `mensaje`. La tabla `usuario` del SQL tampoco incluye todos los campos que espera la aplicación, como `nombre`, `apellidos` y `lvl`, y no existe la tabla `ficha`.

Por tanto, **importar el SQL tal como está no basta para ejecutar la aplicación**. Hay que sustituirlo por el esquema correcto de control horario o preparar un nuevo script que cree las tablas y columnas requeridas. No se incluye aquí un script de reemplazo como si estuviera validado, porque antes conviene decidir los tipos, restricciones y reglas de negocio definitivos.

## Funcionamiento del fichaje

### 1. Inicio de sesión

1. El usuario abre la aplicación y llega a `web/index.php`.
2. El enrutador inicia la sesión y asigna `lvl = 0` si todavía no hay nivel guardado.
3. El visitante accede al formulario de login.
4. El método `Controlador::login()` recoge el nombre de usuario y la contraseña.
5. `Conectate` consulta la tabla `usuario` para comprobar las credenciales.
6. Si encuentra un registro, guarda en `$_SESSION` datos como el nombre de usuario, ID, foto, nombre, apellidos y nivel.
7. El controlador redirige a la página de inicio.

La implementación compara la contraseña directamente con el valor de la base de datos. Esto debe cambiarse por contraseñas almacenadas mediante hash.

### 2. Fichar entrada

Al pulsar **entrada** en `inicio.php`:

1. `Controlador::inicio()` obtiene el ID de usuario desde la sesión.
2. Guarda la hora actual como marca temporal Unix con `time()` y prepara una cadena de fecha/hora.
3. Consulta los fichajes existentes del usuario.
4. Comprueba si no hay fichajes previos, si la última jornada tiene salida o si ha pasado el límite temporal configurado.
5. Si la condición se cumple, `insertarEntrada()` inserta un registro en `ficha` con el ID del usuario, la marca temporal y la fecha/hora de entrada.
6. La interfaz muestra un aviso de éxito o error.

El código intenta impedir que se registre una nueva entrada si hay una jornada anterior sin cerrar, salvo en el caso que considera superado el límite temporal.

### 3. Fichar salida

Al pulsar **salida**:

1. El controlador comprueba que el usuario tenga fichajes previos.
2. Consulta la última entrada y verifica que no exista ya una salida para ese registro.
3. Comprueba que no se haya superado el límite temporal.
4. Si las condiciones se cumplen, llama a `modSalida()`.
5. Este método actualiza el registro de fichaje y guarda `SSalida` y `HSalida`.
6. Se muestra un aviso de confirmación o de error.

La lógica de `superaHora()` usa `79200` segundos, equivalentes a 22 horas, aunque algunos mensajes de la interfaz hablan de 24 horas. Conviene unificar el límite y los mensajes para evitar confusiones.

### 4. Consulta de horas

El método `Controlador::horasCotizadas()` consulta los fichajes del usuario mediante `horasMes()` y construye tablas HTML.

La vista intenta ofrecer dos niveles de información:

- **Detalle por fichaje:** fecha/hora de entrada, fecha/hora de salida y duración calculada.
- **Resumen mensual:** agrupa registros por mes y suma los intervalos con entrada y salida registradas.

Después, JavaScript permite alternar la tabla visible mediante un selector. Parte de la lógica de cálculo se encuentra directamente dentro del controlador, lo que dificulta probarla y mantenerla.

**Nota sobre los cálculos:** el código resta una hora en varios puntos y utiliza `date()` para representar duraciones. Esto puede producir resultados incorrectos, especialmente para jornadas largas o acumulados superiores a 24 horas. Se recomienda calcular duraciones como segundos totales y formatearlas como horas y minutos sin tratarlas como una hora del reloj.

### 5. Alta de usuarios

El método `Controlador::alta()` está condicionado al nivel de administrador (`lvl == 2`).

1. El administrador abre el formulario de alta.
2. Introduce nombre, apellidos, nombre de usuario, contraseña y correo.
3. El controlador recoge los campos y ejecuta validaciones mediante las funciones auxiliares.
4. Comprueba si el nombre de usuario ya existe.
5. Si los datos son válidos, intenta insertar el usuario con fecha de alta y nivel de empleado (`lvl = 1`).
6. Redirige a una página de éxito o error.

El código de alta inserta campos que no existen en la tabla `usuario` del SQL incluido. Además, deben corregirse las validaciones que usan asignaciones en condiciones y almacenarse contraseñas mediante hash.

### 6. Borrado de usuarios

El método `Controlador::borrarCuenta()` también requiere el nivel de administrador. Solicita el nombre de usuario y una casilla de confirmación, y llama al método `Conectate::borrarCuenta()`.

Este método ejecuta un `DELETE` sobre la tabla `usuario`. No es un borrado lógico. Si hay fichajes asociados y existe una clave foránea que impide borrar usuarios referenciados, la operación puede fallar. El comportamiento debe definirse explícitamente: conservar el historial, anonimizar al usuario o permitir una eliminación controlada.

### 7. Cierre de sesión

`Controlador::logoff()` llama a `session_destroy()` y redirige a la ruta de inicio. Para una implementación más segura, también conviene vaciar `$_SESSION`, invalidar la cookie de sesión y configurar correctamente sus atributos.

## Rutas de la aplicación

Las rutas se seleccionan mediante el parámetro `dir` en `web/index.php`.

| Ruta | Método del controlador | Función |
|---|---|---|
| `?dir=inicio` | `inicio()` | Fichaje de entrada y salida |
| `?dir=login` | `login()` | Autenticación |
| `?dir=alta` | `alta()` | Registro de usuario, restringido a administrador |
| `?dir=horasCotizadas` | `horasCotizadas()` | Consulta y resumen de horas |
| `?dir=borrarCuenta` | `borrarCuenta()` | Borrado de usuario, restringido a administrador |
| `?dir=logoff` | `logoff()` | Cierre de sesión |
| `?dir=exito` | `exito()` | Mensaje de operación completada |
| `?dir=error` | `error()` | Mensaje de error |

El enrutador envía a los invitados al login, salvo el acceso a `alta`, pero el método `alta()` requiere nivel de administrador. Esto significa que el registro público no está habilitado en el flujo actual.

## Instalación y configuración

### Requisitos

- PHP con la extensión `PDO` y el driver `pdo_mysql`.
- MySQL o MariaDB.
- Apache u otro servidor web compatible con PHP.
- Navegador web moderno.

### 1. Configurar la base de datos

Edita `app/Config.php`. Los valores actuales apuntan a `localhost`, base de datos `fichaje`, usuario `root` y contraseña vacía:

```php
class Config {
    static public $dbhost = "localhost";
    static public $dbdata = "fichaje";
    static public $dbuser = "TU_USUARIO";
    static public $dbpass = "TU_PASSWORD";
}
```

Utiliza credenciales adecuadas para tu entorno. Evita publicar contraseñas reales en el repositorio y, en producción, usa variables de entorno o un archivo de configuración fuera del directorio público.

### 2. Preparar el esquema correcto

No importes `foro.sql` esperando obtener una base de datos de fichajes funcional: el archivo contiene tablas de un foro y no crea `ficha`. Prepara o consigue el script SQL correcto para las tablas `usuario` y `ficha`, incluyendo todos los campos que utiliza `Conexion.php` y `Controlador.php`.

### 3. Configurar el servidor web

El directorio público debe apuntar a `web/`, donde se encuentra `index.php`. La carpeta `app/` contiene código PHP y configuración que no debería ser accesible directamente desde HTTP.

### 4. Abrir la aplicación

Inicia Apache y MySQL, configura el esquema y accede a la URL local correspondiente, por ejemplo `http://localhost/` si has configurado `web/` como raíz del sitio.

La aplicación no incluye un instalador automatizado ni un esquema de base de datos de fichajes compatible en el ZIP revisado.

## Problemas conocidos y mejoras

Antes de utilizar el proyecto en un entorno real, conviene abordar estos puntos:

1. **Base de datos incompatible:** `foro.sql` crea `hilo` y `mensaje`, pero la aplicación necesita `ficha` y una tabla `usuario` con campos adicionales.
2. **Contraseñas en texto plano:** el login compara directamente usuario y contraseña. Utiliza `password_hash()` y `password_verify()`.
3. **SQL construido por concatenación:** métodos como `select()`, `insert()`, `borrarCuenta()` y varias consultas incorporan datos directamente en el SQL. Usa consultas preparadas y parámetros en todas las operaciones.
4. **Validación con asignaciones:** algunas condiciones del controlador contienen expresiones como `if (!isset($nombre) || $nombre = "")`, que asignan una cadena en lugar de comparar. Deben corregirse y probarse.
5. **Permisos del servidor:** verifica en cada método que el usuario tenga sesión y rol suficientes; no dependas solamente de los enlaces visibles.
6. **Cálculo de horas:** evita restar una hora manualmente y no uses `date()` para mostrar duraciones acumuladas. Calcula segundos y conviértelos a una duración explícita.
7. **Límite inconsistente:** el código usa 22 horas en `superaHora()`, mientras que los mensajes mencionan 24. Define una única regla.
8. **Fechas y zonas horarias:** guarda fechas y horas con tipos adecuados (`DATETIME` o timestamps normalizados), define la zona horaria y evita almacenar fechas formateadas para presentación como única fuente de datos.
9. **Transacciones y concurrencia:** las comprobaciones de entrada/salida y la escritura deberían protegerse frente a peticiones simultáneas para evitar registros duplicados o secuencias inválidas.
10. **Gestión de sesión:** centraliza `session_start()`, regenera el ID después del login y destruye la sesión de forma completa al salir.
11. **Protección CSRF:** añade tokens CSRF a los formularios que modifican datos, incluidos entrada, salida, alta y borrado de usuarios.
12. **Salida HTML:** escapa con `htmlspecialchars()` los valores dinámicos que se imprimen en plantillas para evitar inyección de HTML o JavaScript.
13. **Credenciales de base de datos:** evita usar `root` sin contraseña y crea un usuario de base de datos con los privilegios mínimos necesarios.
14. **Registro de errores:** registra los detalles técnicos fuera del directorio público y muestra mensajes genéricos al usuario.
15. **Separación de responsabilidades:** traslada los cálculos de horas del controlador a una clase de servicio o funciones de dominio y utiliza plantillas solo para presentar resultados.
16. **Pruebas:** añade pruebas para el primer fichaje, salida, intento de salida sin entrada, doble entrada, fichajes incompletos, límite temporal y sumas mensuales.
17. **Eliminación de cuentas:** define cómo preservar el historial laboral y los datos personales cuando se elimina una cuenta; no borres registros sin considerar sus relaciones.

## Resumen final

Web Fichaje es una aplicación PHP para registrar la entrada y salida de empleados y consultar las horas trabajadas. Distingue entre empleados y administradores, usa sesiones para identificar al usuario, PDO para interactuar con MySQL y Bootstrap para la interfaz.

La idea funcional está clara, pero el repositorio necesita una corrección importante del esquema SQL y varias mejoras de seguridad y cálculo antes de considerarse operativo para uso real. Las prioridades son disponer de las tablas correctas, proteger las contraseñas, parametrizar las consultas SQL, verificar los permisos en el servidor y revisar los cálculos de las horas trabajadas.
