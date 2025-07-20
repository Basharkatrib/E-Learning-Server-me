<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 landscape;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            width: 100%;
            height: 100vh;
            position: relative;
            font-family: Arial, sans-serif;
        }
        .certificate-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .certificate {
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path('images/certificate_template.png') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .content {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: center;
        }
        .name {
            font-size: 50px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .course {
            font-size: 40px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .score {
            font-size: 35px;
            color: #6D28D9;
            margin-bottom: 10px;
        }
        .bottom-content {
            position: absolute;
            bottom: 140px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 100px;
            left: 150px;
        }
        .date, .signature {
            font-size: 18px;
            color: #2c3e50;
        }
        .certificate-number {
            position: absolute;
            bottom: 130px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="content">
                <div class="name">{{ $user->name }}</div>
                <div class="course">{{ $quiz->course->title }}</div>
                <div class="score">Final Score: {{ $score }}%</div>
            </div>
            
            <div class="bottom-content">
                <div class="date">{{ \Carbon\Carbon::parse($issue_date)->format('F d, Y') }}</div>
            </div>
            
            <div class="certificate-number">
                Certificate Number: {{ $certificate_number }}
            </div>
        </div>
    </div>
</body>
</html>
