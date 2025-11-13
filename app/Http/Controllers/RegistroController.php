<?php

// Definimos el namespace del controlador, que sigue la estructura de Laravel
namespace App\Http\Controllers;

// Importamos clases necesarias
use Illuminate\Http\Request; // Para manejar solicitudes HTTP y validar datos
use App\Models\Registro;     // Modelo Eloquent que representa la tabla 'registros'

class RegistroController extends Controller
{
    /**
     * Muestra el formulario de registro
     * 
     * Esta función devuelve la vista 'registro.blade.php' donde el usuario puede
     * ingresar sus datos: nombre, fecha, pasos, calorías y estado.
     */
    public function crear()
    {
        // Devuelve la vista 'registro', sin pasar datos adicionales
        return view('registro');
    }

    /**
     * Guarda los datos del formulario y crea respaldo en CSV
     * 
     * Este método se ejecuta cuando el usuario envía el formulario de registro.
     * Recibe un objeto Request que contiene todos los datos enviados.
     */
    public function guardar(Request $request)
    {
        // 1️ VALIDACIÓN DE DATOS
        // Laravel valida automáticamente los campos y, si hay errores, redirige de vuelta con mensajes
        $request->validate([
            'nombre' => 'required|string|max:50',       // Obligatorio, cadena de máximo 50 caracteres
            'fecha' => 'required|date',                 // Obligatorio, debe ser fecha válida
            'pasos' => 'required|numeric|min:0',       // Obligatorio, número >= 0
            'calorias' => 'required|numeric|min:0',    // Obligatorio, número >= 0
            'estado' => 'required|in:Bien,Normal,Mal', // Obligatorio, solo uno de estos valores
        ]);

        // 2️ GUARDAR EN BASE DE DATOS
        // Usamos Eloquent ORM para crear un nuevo registro en la tabla 'registros'
        Registro::create([
            'nombre' => $request->nombre,       // Campo 'nombre' del formulario
            'fecha' => $request->fecha,         // Campo 'fecha' del formulario
            'pasos' => $request->pasos,         // Campo 'pasos' del formulario
            'calorias' => $request->calorias,   // Campo 'calorias' del formulario
            'estado' => $request->estado,       // Campo 'estado' del formulario
        ]);

        // 3️ GUARDAR EN ARCHIVO CSV
        try {
            // storage_path('app/registros.csv') → ruta absoluta del archivo en storage/app
            $fullPath = storage_path('app/registros.csv');

            // Si el archivo no existe, creamos la cabecera CSV
            if (!file_exists($fullPath)) {
                $cabecera = "Nombre,Fecha,Pasos,Calorías,Estado" . PHP_EOL;
                // file_put_contents() con LOCK_EX evita que otro proceso escriba al mismo tiempo
                file_put_contents($fullPath, $cabecera, LOCK_EX);
            }

            // Creamos la línea de datos separada por comas
            $linea = implode(',', [
                $request->nombre,
                $request->fecha,
                $request->pasos,
                $request->calorias,
                $request->estado,
            ]) . PHP_EOL; // PHP_EOL asegura salto de línea correcto en cualquier OS

            // Agregamos la línea al final del archivo
            file_put_contents($fullPath, $linea, FILE_APPEND | LOCK_EX);

        } catch (\Exception $e) {
            // Si hay un error al escribir el CSV, redirigimos de vuelta con mensaje de error
            return redirect()->back()->with('error', 'Error al guardar el registro en CSV.');
        }

        // 4️ REDIRECCIÓN A HISTORIAL CON MENSAJE DE ÉXITO
        // redirect()->route('registro.index') → redirige a la ruta que muestra todos los registros
        // with('success', ...) → agrega un mensaje temporal de éxito a la sesión
        return redirect()->route('registro.index')->with('success', 'Registro guardado correctamente.');
    }

    /**
     * Muestra el historial de registros
     * 
     * Este método obtiene todos los registros de la base de datos, los ordena por fecha descendente,
     * y los pasa a la vista 'historial.blade.php' usando compact().
     */
    public function index()
    {
        // Eloquent ORM → obtiene todos los registros ordenados por fecha descendente
        $registros = Registro::orderBy('fecha', 'desc')->get();

        // compact('registros') → pasa la variable $registros a la vista
        return view('historial', compact('registros'));
    }
}
