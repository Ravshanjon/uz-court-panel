<!DOCTYPE html>
<html>
<head>
    <title>Judge Details</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans', Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Judge Details</h1>
</div>
<div class="content">
    <p><strong>Name:</strong> {{ $judge->last_name }} {{ $judge->first_name }} {{ $judge->middle_name }}</p>
    <p><strong>Position:</strong> {{ $judge->position->name ?? 'N/A' }}</p>
    <p><strong>Birth Date:</strong> {{ $judge->birth_date }}</p>
    <p><strong>Address:</strong> {{ $judge->address }}</p>
    <p><strong>Judges Stages:</strong> {{ $judge->judges_stages }}</p>
    <!-- Add more fields as needed -->
</div>
</body>
</html>
