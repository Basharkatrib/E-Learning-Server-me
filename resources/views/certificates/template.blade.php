<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
        }
        .certificate {
            border: 10px solid #343a40;
            padding: 50px;
            background: white;
            width: 800px;
            margin: auto;
        }
        h1 {
            font-size: 40px;
        }
        .name {
            font-size: 30px;
            font-weight: bold;
        }
        .course {
            font-size: 24px;
        }
        .date {
            margin-top: 30px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This is to certify that</p>
        <div class="name">{{ $user->name }}</div>
        <p>for successfully completing the quiz in the course:</p>
        <div class="course">{{ $quiz->course->title }}</div>
        <div class="date">Issued on {{ \Carbon\Carbon::parse($certificate->issue_date)->format('F d, Y') }}</div>
        <p>Certificate Number: {{ $certificate->certificate_number }}</p>
    </div>
</body>
</html>
