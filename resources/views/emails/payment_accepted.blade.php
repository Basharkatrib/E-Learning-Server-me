@php($name = $user?->first_name . ' ' . $user?->last_name)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Accepted</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111827; }
        .container { max-width: 560px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; }
        .title { color: #16a34a; }
        .btn { display: inline-block; background: #6D28D9; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; }
        .muted { color: #6b7280; font-size: 12px; }
    </style>
    </head>
<body>
    <div class="container">
        <h2 class="title">Congratulations!</h2>
        <p>Hi {{ $name ?: $user->email }},</p>
        <p>Your payment for the course <strong>"{{ method_exists($course, 'getTranslation') ? $course->getTranslation('title', config('app.locale', 'en')) : $course->title }}"</strong> has been <strong>accepted</strong>.</p>
        <p>You now have full access to all lessons and materials of this course.</p>
        <p>LearNova.</p>
    </div>
</body>
</html>


