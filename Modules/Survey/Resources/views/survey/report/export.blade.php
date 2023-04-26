<table>
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>

    </thead>
    <tbody>
        @foreach ($listDataExport as $data)
            <tr>
                @foreach ($data as $item)
                    <td style="text-align:center">{{ $item }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
