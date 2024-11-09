<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Mail\CitaConfirmadaMail;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    public function determinate_tipo(Request $request)
    {
        $dni = $request->get("dni");

        if (isset($dni)) {
            $user = User::where("dni", trim($dni))->first();
            if ($user) {
                return response()->json(["success" => true, "data" => "Datos del usuario obtenidos con exito.", "tipo" => "Revisión", "user" => $user], 200);
            }
            return response()->json(["success" => true, "data" => "Datos del usuario obtenidos con exito.", "tipo" => "Primera consulta"], 200);
        }
        return response()->json(["success" => false, "data" => "No se ha indicado ningún DNI a buscar."], 200);
    }

    public function save(Request $request)
    {
        $form_citas_name = $request->get("form_citas_name");
        $form_citas_dni = $request->get("form_citas_dni");
        $form_citas_telefono = $request->get("form_citas_telefono");
        $form_citas_email = $request->get("form_citas_email");
        $form_citas_tipo = $request->get("form_citas_tipo");
        $form_citas_id_user = $request->get("form_citas_id_user");
        $form_citas_dia = $request->get("form_citas_dia");
        $form_citas_hora = $request->get("form_citas_hora");

        if (isset($form_citas_dni) && isset($form_citas_telefono) && isset($form_citas_name) && isset($form_citas_email) && isset($form_citas_tipo) && isset($form_citas_dia) && isset($form_citas_hora)) {

            if (isset($form_citas_id_user)) {
                // Si tenemos ya el usuario entonces actualizamos los datos
                $user = User::find($form_citas_id_user);
                $user->name = $form_citas_name;
                $user->dni = $form_citas_dni;
                $user->telefono = $form_citas_telefono;
                $user->email = $form_citas_email;
                $user->update();
            } else {
                $check_email_unique = User::where("email", $form_citas_email)->first();
                if ($check_email_unique) {
                    return response()->json(["success" => false, "data" => "El correo ya se encuentra en uso, por favor indique un correo que no este en uso."]);
                }
                // Si no, creamos el usuario
                $user = User::create([
                    "name" => $form_citas_name,
                    "dni" => $form_citas_dni,
                    "telefono" => $form_citas_telefono,
                    "email" => $form_citas_email,
                    'password' => Hash::make("demo")
                ]);
            }

            if (isset($user)) {
                // Comprobamos si ya hay una cita en esa fecha y hora
                $cita_exist = Cita::where('date', $form_citas_dia)
                    ->where('hour', $form_citas_hora)
                    ->exists();

                if ($cita_exist) {
                    return response()->json(["success" => false, "data" => "No se encuentran disponibles el día y la hora para su cita."]);
                }

                // Entonces creamos la Cita para este usuario.
                $cita = Cita::create([
                    "id_user" => $user->id,
                    "user_name" => $user->name,
                    "user_email" => $user->email,
                    "user_telefono" => $user->telefono,
                    "user_dni" => $user->dni,
                    "tipo" => $form_citas_tipo,
                    "hour" => $form_citas_hora,
                    "date" => $form_citas_dia
                ]);
                if ($cita) {
                    try {
                        Mail::to($user->email)->send(new CitaConfirmadaMail($cita));
                    } catch (\Exception $e) {
                        return response()->json(["success" => false, "data" => "Se ha producido un error al enviar el correo: " . $e->getMessage()], 500);
                    }
                    return response()->json(["success" => true, "data" => "Cita creada con exito."], 200);
                } else {
                    return response()->json(["success" => false, "data" => "Se ha producido un error al crear la cita."], 200);
                }
            }
        }
        return response()->json(["success" => false, "data" => "No se ha indicado algún valor del formulario."], 200);
    }
}
