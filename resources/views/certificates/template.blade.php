<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .certificate {
            position: relative;
            width: 100%;
            height: 100vh;
            padding: 40px;
            box-sizing: border-box;
            background: #ffffff;
        }
        .border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #c4a962;
            border-radius: 5px;
        }
        .inner-border {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 5px solid #c4a962;
            border-radius: 2px;
        }
        .content {
            position: relative;
            z-index: 1;
            padding: 50px;
            margin-top: 40px;
        }
        h1 {
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.1);
        }
        .intro {
            font-size: 20px;
            color: #666;
            margin-bottom: 10px;
        }
        .name {
            font-size: 36px;
            font-weight: bold;
            color: #c4a962;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .course {
            font-size: 28px;
            color: #2c3e50;
            margin: 20px 0;
            font-weight: bold;
        }
        .score {
            font-size: 24px;
            color: #27ae60;
            margin: 25px 0;
            font-weight: bold;
        }
        .date {
            font-size: 18px;
            color: #666;
            margin: 20px 0;
        }
        .certificate-number {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            font-size: 14px;
            color: #666;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 130px;
            color: rgba(196, 169, 98, 0.05);
            z-index: 0;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 10px;
        }
        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 120px;
            height: 120px;
            border: 4px solid #c4a962;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #c4a962;
            transform: rotate(-15deg);
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border"></div>
        <div class="inner-border"></div>
        <div class="watermark">Certificate</div>
        
        <div class="content">
            <h1>Certificate of Completion</h1>
            <div class="intro">This is to certify that</div>
            <div class="name">{{ $user->name }}</div>
            <div class="intro">has successfully completed the quiz in the course</div>
            <div class="course">{{ $quiz->course->title }}</div>
            <div class="score">Final Score: {{ $score }}%</div>
            <div class="date">Issued on {{ \Carbon\Carbon::parse($issue_date)->format('F d, Y') }}</div>
            
            <div class="seal">
                VERIFIED
            </div>
        </div>
        
        <div class="certificate-number">
            Certificate Number: {{ $certificate_number }}
        </div>
    </div>
</body>
</html>
