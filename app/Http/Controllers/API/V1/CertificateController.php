<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\{
    Certificate,
    Course,
    Quiz,
    QuizAttempt
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateIssued;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    public function generate(Request $req, Course $course)
    {
        $user = $req->user();
        $quiz = Quiz::with("course")->findOrFail($req->quiz_id);
        
        // Get the latest completed attempt for this quiz
        $attempt = QuizAttempt::where("user_id", $user->id)
            ->where("quiz_id", $quiz->id)
            ->where("status", "completed")
            ->latest()
            ->firstOrFail();

     

        // Check if certificate already exists
        $existing = Certificate::where("quiz_id", $quiz->id)
            ->where("user_id", $user->id)
            ->first();
            
        if ($existing) {
            return response()->json([
                "message" => "Certificate already issued.",
                "data" => [
                    "certificateUrl" => $existing->file_path
                ]
            ]);
        }

        try {
            // Create certificate number
            $certificateNumber = strtoupper(Str::random(10));
            
            // Generate PDF
            $pdf = Pdf::loadView("certificates.template", [
                "user" => $user,
                "quiz" => $quiz,
                "score" => $attempt->score,
                "certificate_number" => $certificateNumber,
                "issue_date" => now()
            ]);

            // تعيين إعدادات PDF المبسطة
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('margin-top', '0mm');
            $pdf->setOption('margin-right', '0mm');
            $pdf->setOption('margin-bottom', '0mm');
            $pdf->setOption('margin-left', '0mm');

            // Save PDF to temporary file
            $tempPath = storage_path('app/temp/' . $certificateNumber . '.pdf');
            if (!is_dir(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            $pdf->save($tempPath);

            // Create UploadedFile instance
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempPath,
                $certificateNumber . '.pdf',
                'application/pdf',
                filesize($tempPath),
                UPLOAD_ERR_OK,
                true
            );

            // Store file in Cloudinary in the courses folder
            $path = Storage::disk('cloudinary')->putFileAs(
                'courses',
                $uploadedFile,
                $certificateNumber . '.pdf',
                [
                    'resource_type' => 'raw'
                ]
            );

            // Get the secure URL with version number
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $version = time(); // This will simulate the version number
            $certificateUrl = "https://res.cloudinary.com/{$cloudName}/raw/upload/v{$version}/courses/{$certificateNumber}.pdf";

            // Create certificate record
            $certificate = Certificate::create([
                "user_id" => $user->id,
                "quiz_id" => $quiz->id,
                "quiz_attempt_id" => $attempt->id,
                "certificate_number" => $certificateNumber,
                "issue_date" => now(),
                "file_path" => $certificateUrl
            ]);

            // Try to send email
            try {
                Mail::to($user->email)->send(new CertificateIssued($certificate));
            } catch (\Exception $mailError) {
                Log::error('Failed to send certificate email', [
                    'error' => $mailError->getMessage(),
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                // We don't throw the error here as the certificate is already generated
            }

            // Delete temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return response()->json([
                "status" => "success",
                "message" => "Certificate generated successfully.",
                "data" => [
                    "certificateUrl" => $certificateUrl,
                    "certificate_number" => $certificateNumber
                ]
            ], 200);

        } catch (\Exception $e) {
            // Clean up temporary file if it exists
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }

            return response()->json([
                "status" => "error",
                "message" => "Error generating certificate. Please try again."
            ], 500);
        }
    }
}
