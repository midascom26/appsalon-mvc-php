<?php

namespace Controllers; // Definir un espacio de nombres para la clase

use MVC\Router; // Importar la clase Router
use Model\Usuario; // Importar la clase Usuario
use Classes\Email; // Importar la clase Email

// Controlador para el login
class LoginController
{

    //-----------------------------login--------------------------------------

    // Método para iniciar sesión
    public static function login(Router $router)
    {
        $alertas = []; // Arreglo para las alertas de errores

        // Verificar si se está enviando el formulario de login con el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); // Crear un nuevo objeto Usuario con los datos del formulario
            $alertas = $auth->validarLogin(); // validar el login - retorna un arreglo con las alertas de errores en caso de que hayan

            // Revisar si el arreglo de alertas está vacío, si está vacío no hay errores
            if (empty($alertas)) { // Si no hay errores
                // Verificar si el usuario existe en la base de datos
                $usuario = Usuario::where('email', $auth->email); // Retorna el usuario con el email, si existe

                // Verificar el password del usuario
                if ($usuario) { // Si el usuario existe
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) { // Comprobar el password y si el usuario está verificado
                        // Autenticar el usuario
                        session_start(); // Iniciar la sesión
                        $_SESSION['id'] = $usuario->id; // Asignar el id del usuario a la sesión
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido; // Asignar el nombre del usuario a la sesión
                        $_SESSION['email'] = $usuario->email; // Asignar el email del usuario a la sesión
                        $_SESSION['login'] = true; // Asignar true a la sesión login

                        // Redireccionamiento a la vista /admin o /cita dependiendo del rol del usuario
                        if ($usuario->admin === "1") { // Si el usuario es admin
                            $_SESSION['admin'] = $usuario->admin ?? null; // Asignar el rol de admin a la sesión
                            header('Location: /admin'); // Redireccionar al usuario a la vista /admin
                        } else { // Si no es admin (osea un usuario normal)
                            header('Location: /cita'); // Redireccionar al usuario a la vista /cita
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe'); // Asignamos el mensaje de error
                }
            }
        }

        $alertas = Usuario::getAlertas(); // Obtener las alertas de error

        // Mostrar la vista auth/login
        $router->render('auth/login', [
            'alertas' => $alertas, // Pasamos las alertas a la vista para que estén disponibles en el formulario
        ]);
    }
    //-------------------------------------------------------------------------

    //-----------------------------logout--------------------------------------

    public static function logout()
    {
        session_start(); // Iniciar la sesión
        $_SESSION = []; // Limpiar la sesión
        header('Location: /'); // Redireccionar al usuario a la vista/login
    }
    //-------------------------------------------------------------------------

    //-----------------------------olvide--------------------------------------

    // Método para cuando se olvida el password
    public static function olvide(Router $router)
    {
        $alertas = []; // Arreglo para las alertas de errores

        // Verificar si se está enviando el formulario de olvide password con el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); // Crear un nuevo objeto Usuario con los datos del formulario
            $alertas = $auth->validarEmail(); // Validar el email
            
            if (empty($alertas)) { // Si no hay errores
                // Verificar si el usuario existe en la base de datos
                $usuario = Usuario::where('email', $auth->email); // Retorna el usuario con el email, si existe

                if ($usuario && $usuario->confirmado === "1") { // Si el usuario existe y está confirmado
                    $usuario->crearToken(); // Generar un token único para el usuario
                    $usuario->guardar(); // Guardar el token en la base de datos

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token); // Crear un nuevo objeto Email
                    $email->enviarInstrucciones(); // Enviar el Email con las instrucciones para recuperar el password

                    // Asignamos el mensaje de éxito
                    Usuario::setAlerta('exito', 'Se ha enviado un email con las instrucciones para recuperar tu password');
                } else { // Si el usuario no existe
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado'); // Asignamos el mensaje de error
                }
            }
        }

        $alertas = Usuario::getAlertas(); // Obtener las alertas de error

        // Mostrar la vista auth/olvide-password
        $router->render('auth/olvide-password', [
            'alertas' => $alertas // Pasamos las alertas a la vista para que estén disponibles en el formulario
        ]);
    }
    //--------------------------------------------------------------------------

    //-----------------------------recuperar------------------------------------

    // Método para recuperar el password
    public static function recuperar(Router $router)
    {
        $alertas = []; // Arreglo para las alertas de errores
        $error = false; // Se utiliza la variable error para que no se muestre el formulario en la vista si hay un error

        $token = s($_GET['token']); // Obtener el token de la URL y sanitizarlo
        
        // Buscar el usuario con el token
        $usuario = Usuario::where('token', $token); // Retorna el usuario con el token, si existe

        // Si no se encontró el usuario con el token
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido'); // Asignamos el mensaje de error
            $error = true; // Asignamos true a la variable error
        }

        // Verificar si se está enviando el formulario de recuperar password con el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST); // Crear un nuevo objeto Usuario con los datos del formulario
            $alertas = $password->validarPassword(); // Validar el password - retorna un arreglo con las alertas de errores en caso de que hayan
        
            if (empty($alertas)) { // Si no hay errores
                $usuario->password = null; // Limpiar el password por seguridad
                $usuario->password = $password->password; // Asignar el nuevo password al usuario
                $usuario->hashPassword(); // Hashear el nuevo password
                $usuario->token = null; // Limpiar el token por seguridad
                $resultado = $usuario->guardar(); // Guardar el nuevo password en la base de datos
                
                if ($resultado) { // Si se guardó el nuevo password en la base de datos
                    header('Location: /'); // Redireccionar al usuario a la vista /login
                }
            }
        }

        $alertas = Usuario::getAlertas(); // Obtener las alertas de error

        // Mostrar la vista auth/recuperar-password
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas, // Pasamos las alertas a la vista para que estén disponibles en el formulario
            'error' => $error // Pasamos el error a la vista para que esté disponible en el formulario
        ]);
    }
    //--------------------------------------------------------------------------

    //-----------------------------crear----------------------------------------

    // Crear una cuenta
    public static function crear(Router $router)
    {
        // Creamos el usuario por fuera del if para que no se pierda la variable y conserve los datos y los muestre en el formulario
        // Si se recarga la pagina los datos siguen apareciendo en el formulario
        // Importante: En el formulario de la vista auth/crear-cuenta se debe hacer referencia a cada campo del objeto usuario
        $usuario = new Usuario; // Crear un nuevo usuario vacío

        $alertas = []; // Arreglo para las alertas de errores

        // Verificar si se está enviando el formulario de crear cuenta con el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincroniza el objeto en memoria con los cambios realizados por el usuario en el formulario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta(); // Validar los datos del formulario

            // Revisar si el arreglo de alertas está vacío, si está vacío no hay errores
            if (empty($alertas)) {
                // Verificar si el usuario ya existe en la base de datos, retorna el objeto de la consulta
                $resultado = $usuario->existeUsuario();

                // Si el resultado tiene filas, el usuario ya existe
                if ($resultado->num_rows) {
                    // Almacenamos las alertas de error en el arreglo $alertas
                    $alertas = Usuario::getAlertas(); // Obtener las alertas de error
                } else { // Si el usuario no existe
                    $usuario->hashPassword(); // Hashear el password

                    // Generar un token único para el usuario
                    $usuario->crearToken();

                    // Crear el objeto Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token); // Crear un nuevo objeto Email
                    $email->enviarConfirmacion(); // Enviar el Email de confirmación

                    // Guardar el usuario en la base de datos
                    $resultado = $usuario->guardar();

                    if ($resultado) { // Si se guardó el usuario en la base de datos
                        header('Location: /mensaje'); // Redireccionar al usuario a la página de mensaje
                    }
                }
            }
        }

        // Mostrar la vista auth/crear-cuenta con los datos del usuario y las alertas de errores si existen
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario, // Pasamos el usuario a la vista para que esté disponible en el formulario,
            'alertas' => $alertas // Pasamos las alertas a la vista para que estén disponibles en el formulario
        ]);
    }
    //--------------------------------------------------------------------------

    //-----------------------------mensaje--------------------------------

    public static function mensaje(Router $router)
    {
        // Mostrar la vista auth/mensaje
        $router->render('auth/mensaje'); // Mostrar la vista auth/mensaje
    }
    //--------------------------------------------------------------------------

    //-----------------------------confirmar--------------------------------
    
    public static function confirmar(Router $router)
    {
        $alertas = []; // Arreglo para las alertas de errores

        $token = s($_GET['token']); // Obtener el token de la URL y sanitizarlo

        // Consultar la base de datos para verificar si el token es válido
        $usuario = Usuario::where('token', $token); // Retorna el usuario con el token, si existe

        // Si no se encontró el usuario con el token
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido'); // Asignamos el mensaje de error
        } else { // Si se encontró el usuario con el token
            // Activar el usuario
            // Modificar el campo 'confirmado' de la tabla usuarios a 1
            $usuario->confirmado = "1"; // Activar el usuario
            $usuario->token = null; // Limpiar el token por seguridad
            $usuario->guardar(); // Guardar el usuario en la tabla usuarios de la base de datos
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente'); // Asignamos el mensaje de éxito
        }

        $alertas = Usuario::getAlertas(); // Obtener las alertas de error

        // Mostrar la vista auth/confirmar-cuenta con las alertas de errores
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas // Pasamos las alertas a la vista para que estén disponibles en el formulario
        ]);
    }
}
