<!-- <?php -->

// namespace App\Observers;

// use App\Models\Enrollment;
// use App\Services\FirebaseService;
// use Illuminate\Support\Facades\Log;

// class EnrollmentAcceptedObserver
// {

//     protected $firebaseService;

//     public function __construct(FirebaseService $firebaseService)
//     {
//         $this->firebaseService = $firebaseService;
//     }

//     /**
//      * Handle the Enrollment "created" event.
//      */
//     public function created(Enrollment $enrollment): void
//     {
//         //
//     }

//     /**
//      * Handle the Enrollment "updated" event.
//      */
//     public function updated(Enrollment $enrollment): void
//     {

//         if ($enrollment->isDirty("status") && $enrollment->status === "accepted") {

//             try {
//                 $enrollment->load("course");

//                 $title = "Course Enrollment Accepted 🎉";
//                 $body = "You have been accepted to the course: " . $enrollment->course->title;

//                 $this->firebaseService->sendToUser(
//                     $enrollment->user_id,
//                     $title,
//                     $body,
//                     [
//                         "type" => "enrollment_accepted",
//                         "course_id" => (string) $enrollment->course_id,
//                         "course_title" => $enrollment->course->title,
//                         "status" => "accepted",
//                         "timestamp" => now()->toISOString()
//                     ]
//                 );

//                 Log::info("Enrollment notification sent to user {$enrollment->user_id} for course {$enrollment->course->title}");
//             } catch (\Exception $e) {
//                 Log::error("Failed to send enrollment notification: " . $e->getMessage());
//             }
//         }
//     }

//     /**
//      * Handle the Enrollment "deleted" event.
//      */
//     public function deleted(Enrollment $enrollment): void
//     {
//         //
//     }

//     /**
//      * Handle the Enrollment "restored" event.
//      */
//     public function restored(Enrollment $enrollment): void
//     {
//         //
//     }

//     /**
//      * Handle the Enrollment "force deleted" event.
//      */
//     public function forceDeleted(Enrollment $enrollment): void
//     {
//         //
//     }
// }
