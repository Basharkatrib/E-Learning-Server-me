<p>Hi {{ $certificate->user->name }},</p>
<p>Congratulations! You have successfully completed the course <strong>{{ $certificate->quiz->course->title }}</strong>.</p>
<p>You can download your certificate from this link: <a href="{{ $certificate->file_path }}">Download Certificate</a></p>
<p>Best regards,<br/>Learning Platform</p>