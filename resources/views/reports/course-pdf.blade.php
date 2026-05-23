<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Analytics Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        .weak { background: #fee2e2; }
        .strong { background: #dcfce7; }
        .completion { background: #dbeafe; }
    </style>
</head>
<body>

<h1>Course Analytics Report</h1>

<table>
    <thead>
        <tr>
            <th>Course</th>
            <th>Active Students</th>
            <th>Avg Score</th>
            <th>Attempts</th>
            <th>Weak Topics</th>
            <th>Strong Topics</th>
            <th>Completion</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($analytics as $item)
            <tr>
                <td>{{ $item['course']->title }}</td>
                <td>{{ $item['active_students'] }}</td>
                <td>{{ $item['avg_score'] }}%</td>
                <td>{{ $item['total_attempts'] }}</td>
                <td>
                    <span class="badge weak">
                        {{ $item['weak_topics'] }}
                    </span>
                </td>
                <td>
                    <span class="badge strong">
                        {{ $item['strong_topics'] }}
                    </span>
                </td>
                <td>
                    <span class="badge completion">
                        {{ $item['completion_ratio'] }}%
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>