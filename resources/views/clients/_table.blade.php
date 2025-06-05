@forelse ($clients as $client)
    <tr>
        <td>{{ $client->name }}</td>
        <td>{{ $client->email }}</td>
        <td>{{ $client->phone }}</td>
        <td>{{ $client->document }}</td>
        <td>{{ $client->address }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted py-4">
            <i class="bi bi-exclamation-circle text-warning"></i> Nenhum cliente encontrado.
        </td>
    </tr>
@endforelse
