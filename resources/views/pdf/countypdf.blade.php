<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
    @page {
        margin: 80px 40px 80px 40px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    header {
        position: fixed;
        top: -50px;
        left: 0;
        right: 0;
        height: 30px;
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #666;
    }

    .logo {
        height: 40px;
    }

    table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

    th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

    th {
            background-color: #f3f3f3;
        }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('images/logo.png') }}" class="logo"> 
</header>

<footer>
    {{ now()->format('Y-m-d H:i') }} • Laravel ZIP api
</footer>

<main>
    <h2>County: {{ $county['name'] }}</h2>
    <p><strong>County ID:</strong> {{ $county['id'] }}</p>

    <table>
        <thead>
            <tr>
                <th>Place ID</th>
                <th>Place Name</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($places))
                <tr>
                    <td>{{ $county['id'] }}</td>
                    <td>{{ $county['name'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @else
                @foreach($places as $place)
                    <tr>
                        <td>{{ $place['id'] }}</td>
                        <td>{{ $place['name'] }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</main>

</body>
</html>
