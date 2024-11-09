<?php

namespace App\Http\Controllers;

use App\DataTables\CitasDataTable;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitasController extends Controller
{
    public function index(CitasDataTable $dataTable)
    {
        return $dataTable->render('citas.index');
    }


    public function check_date(Request $request)
    {
        $dia = $request->get("dia");
        $hora = $request->get("hora");

        if (isset($dia) && isset($hora)) {
            // Verificar que la fecha solicitada no sea inferior a la de hoy
            if ($dia < Carbon::now()->toDateString()) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'La fecha solicitada no puede ser anterior a hoy.',
                ]);
            }

            // Comprobamos si ya hay una cita en esa fecha y hora
            $cita_exist = Cita::where('date', $dia)
                ->where('hour', $hora)
                ->exists();

            if ($cita_exist) {
                $next_available_hour = $this->get_next_available_hour($dia, $hora);

                return response()->json([
                    'success' => false,
                    'data' => 'La hora solicitada ya está ocupada. La siguiente hora disponible es ' . $next_available_hour,
                    'next_available_hour' => $next_available_hour
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => 'El día y hora introducidos están disponibles.',
            ]);
        }
        return response()->json([
            'success' => false,
            'data' => 'Debe indicar fecha y hora para poder comprobar disponibilidad.',
        ]);

    }

    private function get_next_available_hour($dia, $hora): string
    {
        // Convertimos la hora solicitada en un formato de hora
        $time = Carbon::createFromFormat('H:i', $hora);

        // Buscamos la siguiente hora disponible
        for ($i = 1; $i <= 12; $i++) { // Hasta 12 horas siguientes
            $next_hour = $time->addHour(); // Sumar una hora

            // Comprobamos si ya hay una cita a esa hora
            $cita_exist = Cita::where('date', $dia)
                ->where('hour', $next_hour->format('H:i'))
                ->exists();

            if (!$cita_exist) {
                // Si la hora está disponible, la devolvemos
                return $next_hour->format('H:i');
            }
        }
        return 'No hay disponibilidad en el rango solicitado.';
    }
}
