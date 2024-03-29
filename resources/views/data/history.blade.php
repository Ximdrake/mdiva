@extends(backpack_view('layouts.' . (backpack_theme_config('layout') ?? 'vertical')))

@section('content')
    <div class="container">
        <h1>History</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Timestamp</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        
                        <td>{{ $item['description'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('F j, Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection