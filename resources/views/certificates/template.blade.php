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
            background-image: url('{{ public_path('images/certeficate_template.png') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .certificate2 {
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path('images/certeficate_template2.png') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .content {
            position: absolute;
            top: 48%;
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
            top: 55%;
            width: 100%;
            left: -9%;
            padding: 0 100px;
            font-size: 25px;
            text-align: center;
        }
      
    </style>
</head>
<body>
    <div class="certificate-container">
        @if($score < 70)
            <div class="certificate">
                <div class="content">
                    <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
                </div>
                <div class="bottom-content">
                    <i>
                       This certificate is presented to the student who has attended {{ $quiz->course->duration }} <br/> out of  {{ $quiz->course->duration }} in the <b>{{ $quiz->course->title }}</b> course.
                    </i>
                </div>
            </div>
        @else
            <div class="certificate2">
                <div class="content">
                    <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
                </div>
                <div class="bottom-content">
                    <i>
                       This certificate is presented to the student who has attended {{ $quiz->course->duration }} <br/> out of  {{ $quiz->course->duration }} in the <b>{{ $quiz->course->title }}</b> course <br /> and passed the final exam with a score of {{ $score }}%.
                    </i>
                </div>
            </div>
        @endif
       
    </div>
</body>
</html>
