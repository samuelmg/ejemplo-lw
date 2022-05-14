<h2>Hola, estas son tus tareas:</h2>

<table>
    <tr><th>Tarea</th></tr>
    @foreach($tareas as $tarea)
        <tr>
            <td>{{ $tarea->tarea }}</td>
        </tr>
    @endforeach
</table>
