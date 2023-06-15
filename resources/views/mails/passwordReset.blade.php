@component('mail::message')
Hola

Estás recibiendo este correo por que hiciste una solicitud de recuperacion de
contraseña para tu cuenta.

@component('mail::button', ['url' => 'https://frontend-indicadores.devaztweb.com/admin/nuevo-password?token='.$token])
Recuperar contraseña
@endcomponent

Si no realizaste esta solicitud, no se requiere realizar ninguna otra acción.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
