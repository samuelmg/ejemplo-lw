@component('mail::message')
# Tareas

The body of your message.

@component('mail::table')
| Tareas       |
| ------------ |
@foreach($tareas as $t)
| {{ $t->tarea }} |
@endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
