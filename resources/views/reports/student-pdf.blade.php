<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Analytics Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary div {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
    </style>
</head>
<body>

<h1>Student Analytics Report</h1>
<p class="subtitle">{{ $student->name }} ({{ $student->email }})</p>

<div class="summary">
    <div><strong>Total Courses:</strong> {{ $totalCourses }}</div>
    <div><strong>Average Score:</strong> {{ $avgScore }}%</div>
    <div><strong>Total Attempts:</strong> {{ $totalAttempts }}</div>
    <div><strong>Weak Topics:</strong> {{ $totalWeakTopics }}</div>
    <div><strong>Strong Topics:</strong> {{ $totalStrongTopics }}</div>
</div>

<table>
    <thead>
        <tr>
            <th>Course</th>
            <th>Avg Score</th>
            <th>Weak Topics</th>
            <th>Strong Topics</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($performances as $performance)
            <tr>
                <td>{{ $performance->course->title ?? 'N/A' }}</td>
                <td>{{ $performance->average_score }}%</td>
                <td>
                    <span class="badge weak">
                        {{ $performance->weak_topics_count }}
                    </span>
                </td>
                <td>
                    <span class="badge strong">
                        {{ $performance->strong_topics_count }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>