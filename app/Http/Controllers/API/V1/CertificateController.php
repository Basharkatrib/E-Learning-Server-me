<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Mail\CertificateIssued;
use App\Models\{
    Certificate,
    Course,
    Quiz,
    QuizAttempt
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function generate(Request $req, Course $course)
    {
        $user = $req->user();
        $quiz = Quiz::with("course")->findOrFail($req->quiz_id);
        $attempt = QuizAttempt::where("id", $req->attempt_id)
            ->where("user_id", $user->id)
            ->where("quiz_id", $quiz->id)
            ->firstOrFail();

        //  Check if attempt is completed
        if ($attempt->status !== "completed") {
            return response()->json([
                "message" => "Quiz isn't completed yet!"
            ], 400);
        }

        // Check if passing score is met
        if ($quiz->passing_score && $attempt->score < $quiz->passing_score) {
            return response()->json([
                "message" => "Score didn't match the passing score",
            ], 403);
        }

        // Check if certificate already exists
        $existing = Certificate::where("quiz_attempt_id", $attempt->id)->first();
        if ($existing) {
            return response()->json([
                "message" => "Certificate already issued.",
                "certificateUrl" => asset("storage/{$existing->file_path}")
            ]);
        }

        //create the certificate
        $certificateNumber = strtoupper(Str::random(10));
        $issueDate = now();

        $certificate = Certificate::create([
            "user_id" => $user->id,
            "quiz_id" => $quiz->id,
            "quiz_attempt_id" => $attempt->id,
            "certificate_number" => $certificateNumber,
            "issue_date" => $issueDate,
        ]);

        //Generate PDF
        $pdf = Pdf::loadView("certificates.template", [
            "user" => $user,
            "quiz" => $quiz,
            "certificate" => $certificate,
        ]);

        $filePath = "certificates/{$certificateNumber}.pdf";
        Storage::put("public/{$filePath}", $pdf->output());

        $certificate->update(["file_path" => $filePath]);

        // Email the certificate
        Mail::to($user->email)->send(new CertificateIssued($certificate));

        return response()->json([
            "message" => "Certificate generated and emailed.",
            "certificateUrl" => asset("storage/{$certificate->file_path}")
        ]);
    }
}
