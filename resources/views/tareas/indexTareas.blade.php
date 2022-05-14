<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tareas</title>
</head>
<body>
    <h1>Listado de Tareas</h1>

    <a href="/enviar-reporte">Enviar Correo con Reporte de Tareas</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <a href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                        this.closest('form').submit();">
            Salir
        </a>
    </form>

    <a href="/tarea/create">Crear Nueva Tarea</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Tarea</th>
            <th>Descripción</th>
            <th>Categoria</th>
            <th>Etiquetas</th>
            <th>Acciones</th>
            <th>Domicilio</th>
        </tr>

        @foreach($tareas as $tarea)
            <tr>
                <td>{{ $tarea->id }}</td>
                <td>{{ $tarea->user->nombre_correo }}</td>
                <td>{{ $tarea->tarea }}</td>
                <td>{{ $tarea->descripcion }}</td>
                <td>{{ $tarea->categoria }}</td>
                <td>
                    @foreach ($tarea->etiquetas as $etiqueta)
                        {{ $etiqueta->etiqueta }} <br>
                    @endforeach
                </td>
                <td>
                    @can('view', $tarea)
                        <a href="/tarea/{{ $tarea->id }}">Ver Detalle</a> |
                    @endcan
                    <a href="/tarea/{{ $tarea->id }}/edit">Editar</a>
                    <form action="/tarea/{{ $tarea->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Eliminar">
                    </form>
                    <br>
                    <form action="/tarea/eliminar-db/{{ $tarea->id }}" method="POST">
                        @csrf
                        <input type="submit" value="Eliminar DB !!!">
                    </form>
                </td>
            </tr>
        @endforeach

    </table>

</body>
</html>