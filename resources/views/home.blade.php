@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(\Illuminate\Support\Facades\Auth::id() == 1)
                    <div class="card mb-7">
                        <div class="card-header">{{ __('Bienvenido al sistema de citas') }}</div>
                        <div class="card-body">
                            <div class="row mb-7">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="col col-md-6 mt-1">
                                    {{ __('Indique DNI para comenzar la búsqueda') }}
                                </div>

                                <div class="col col-md-6">
                                    <input id="dni" type="text"
                                           class="form-control" name="dni"
                                           value="" required autocomplete="dni">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bloque_form_citas" class="card mt-4" style="display: none;">
                        <div class="card-header">{{ __('Gestión') }}</div>
                        <div class="card-body">
                            <div class="row mb-7">
                                <input type="hidden" name="form_citas_id_user" value="">
                                <div class="col col-md-6 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Nombre') }}</label>
                                    <input id="form_citas_name" type="text" placeholder="Introduzca un nombre"
                                           class="form-control" name="form_citas_name"
                                           value="" required>
                                </div>
                                <div class="col col-md-6 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('DNI') }}</label>
                                    <input id="form_citas_dni" type="text" placeholder="Introduzca un DNI"
                                           class="form-control" name="form_citas_dni"
                                           value="" required>
                                </div>
                                <div class="col col-md-6 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Teléfono') }}</label>
                                    <input id="form_citas_telefono" type="number" placeholder="Introduzca un teléfono"
                                           class="form-control" name="form_citas_telefono"
                                           value="" required>
                                </div>
                                <div class="col col-md-6 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Email') }}</label>
                                    <input id="form_citas_email" type="email" placeholder="Introduzca un email"
                                           class="form-control" name="form_citas_email"
                                           value="" required>
                                </div>
                                <div class="col col-md-6 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Tipo de cita') }}</label>
                                    <input disabled readonly id="form_citas_tipo" type="text"
                                           class="form-control" name="form_citas_tipo"
                                           value="">
                                </div>
                                <div class="col col-md-2 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Día') }}</label>
                                    <input id="form_citas_dia" type="date"
                                           class="form-control" name="form_citas_dia"
                                           value="">
                                </div>
                                <div class="col col-md-2 mb-2">
                                    <label class="form-label ms-1 mb-0">{{ __('Hora') }}</label>
                                    <select class="form-select" name="form_citas_hora" id="form_citas_hora">
                                        @for ($hour = 10; $hour <= 22; $hour++)
                                            <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                                                {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2 d-flex justify-content-end align-items-end">
                                    <button id="btn_check_date" type="button" class="btn btn-secondary">
                                        {{ __('Check fechas') }}
                                    </button>
                                </div>
                                <div class="col-md-12 d-flex justify-content-center align-items-center">
                                    <button id="btn_send_cita" type="button" class="btn btn-primary">
                                        {{ __('Solicitar') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mb-7">
                        <div class="card-header">{{ __('Bienvenido al sistema de citas') }}</div>
                        <div class="card-body">
                            {{ __('Puede acceder al listado de sus citas haciendo click en Listado de Citas') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@if(\Illuminate\Support\Facades\Auth::id()==1)
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let typingTimer;
                const typingInterval = 1200; // Tiempo antes del lanzamiento

                // Función para validar el email
                const validateEmail = (email) => {
                    let re = /\S+@\S+\.\S+/;
                    return re.test(email);
                };

                // Evento para controlar el envio de ajax cuando acaben de escribir DNI
                $("#dni").on("keyup", function (e) {
                    e.preventDefault();
                    const dni = $(this).val();

                    clearTimeout(typingTimer);

                    typingTimer = setTimeout(function () {
                        $.ajax({
                            url: '{{route("users.determinate_tipo")}}',
                            method: "GET",
                            data: {dni: dni},
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: "success",
                                        text: response.data,
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(() => {
                                        $("#bloque_form_citas").fadeIn();

                                        // Vaciamos primero los bloques del formulario
                                        $("input[name='form_citas_name']").val("");
                                        $("input[name='form_citas_dni']").val("");
                                        $("input[name='form_citas_telefono']").val("");
                                        $("input[name='form_citas_email']").val("");
                                        $("input[name='form_citas_tipo']").val("");
                                        $("input[name='form_citas_id_user']").val("");
                                        $("input[name='form_citas_dia']").val("");
                                        $("select[name='form_citas_hora']").val("10:00");

                                        if (response.user !== undefined) {
                                            // Si tenemos usuario quiere decir que ese DNI ya esta en uno por tanto es Revisión
                                            $("input[name='form_citas_name']").val(response.user.name);
                                            $("input[name='form_citas_dni']").val(response.user.dni);
                                            $("input[name='form_citas_telefono']").val(response.user.telefono);
                                            $("input[name='form_citas_email']").val(response.user.email);
                                            $("input[name='form_citas_id_user']").val(response.user.id);
                                        } else {
                                            // Si no le indicamos el DNI para que se cree este usuario con este DNI
                                            $("input[name='form_citas_dni']").val(dni);
                                        }
                                        $("input[name='form_citas_tipo']").val(response.tipo);
                                    }, 1500);
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        text: response.data,
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        timer: 1500
                                    });
                                }
                            }, error: function (err) {
                                console.error(err);
                            }
                        })
                    }, typingInterval);
                });

                // Evento por si cambian de foco al escribir en DNI.
                $('#dni').on('keydown', function () {
                    clearTimeout(typingTimer);
                });

                $("#btn_send_cita").off("click").on("click", function (e) {
                    e.preventDefault();

                    let form_citas_name = $("input[name='form_citas_name']").val();
                    let form_citas_dni = $("input[name='form_citas_dni']").val();
                    let form_citas_telefono = $("input[name='form_citas_telefono']").val();
                    let form_citas_email = $("input[name='form_citas_email']").val();
                    let form_citas_id_tipo = $("input[name='form_citas_id_tipo']").val();
                    let form_citas_tipo = $("input[name='form_citas_tipo']").val();
                    let form_citas_id_user = $("input[name='form_citas_id_user']").val();
                    let form_citas_dia = $("input[name='form_citas_dia']").val();
                    let form_citas_hora = $("select[name='form_citas_hora']").val();

                    if (validateEmail(form_citas_email) === false) {
                        Swal.fire({
                            icon: "info",
                            text: "Por favor introduzca un email válido.",
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 1500
                        });
                        return;
                    }

                    Swal.fire({
                        icon: "info",
                        text: "Se va a gestionar una cita, en caso de que el usuario no exista se dara de alta. Estas seguro de querer realizar esta acción?",
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{route("users.save")}}',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                method: "POST",
                                data: {
                                    form_citas_name: form_citas_name,
                                    form_citas_dni: form_citas_dni,
                                    form_citas_telefono: form_citas_telefono,
                                    form_citas_email: form_citas_email,
                                    form_citas_id_tipo: form_citas_id_tipo,
                                    form_citas_tipo: form_citas_tipo,
                                    form_citas_id_user: form_citas_id_user,
                                    form_citas_dia: form_citas_dia,
                                    form_citas_hora: form_citas_hora,
                                },
                                success: function (response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: "success",
                                            text: response.data,
                                            showConfirmButton: false,
                                            showCancelButton: false,
                                            timer: 2000
                                        });

                                        // Vaciamos form
                                        $("input[name='form_citas_name']").val("");
                                        $("input[name='form_citas_dni']").val("");
                                        $("input[name='form_citas_telefono']").val("");
                                        $("input[name='form_citas_email']").val("");
                                        $("input[name='form_citas_id_tipo']").val("");
                                        $("input[name='form_citas_tipo']").val("");
                                        $("input[name='form_citas_id_user']").val("");
                                        $("input[name='form_citas_dia']").val("");
                                        $("select[name='form_citas_hora']").val("");

                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            text: response.data,
                                            showConfirmButton: false,
                                            showCancelButton: false,
                                            timer: 2000
                                        });
                                    }
                                }, error: function (err) {
                                    console.error(err);
                                }
                            })
                        }
                    });
                });

                $("#btn_check_date").off("click").on("click", function (e) {
                    e.preventDefault();
                    let dia = $("input[name='form_citas_dia']").val();
                    let hora = $("select[name='form_citas_hora']").val();

                    $.ajax({
                        url: '{{route("citas.check_date")}}',
                        method: "GET",
                        data: {dia: dia, hora: hora},
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    text: response.data,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    timer: 1500
                                });
                            } else {
                                Swal.fire({
                                    icon: "info",
                                    text: response.data,
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: "Aceptar",
                                    cancelButtonText: "Cancelar"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        if (response.next_available_hour !== undefined) {
                                            $("input[name='form_citas_hora']").val(response.next_available_hour);
                                        }
                                    }
                                });
                            }
                        },
                        error: function (err) {
                            console.error(err);
                        }
                    })
                });
            });
        </script>
    @endpush
@endif
