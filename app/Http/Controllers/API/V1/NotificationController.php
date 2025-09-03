<!-- <?php -->

// namespace App\Http\Controllers\API\V1;

// use App\Http\Controllers\Controller;
// use App\Services\FirebaseService;
// use App\Models\DeviceToken;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;

// class NotificationController extends Controller
// {
//     protected $firebaseService;

//     public function __construct(FirebaseService $firebaseService)
//     {
//         $this->firebaseService = $firebaseService;
//     }

//     public function registerDeviceToken(Request $request)
//     {

//         $user = Auth::user();
//         if (!$user) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Unauthenticated. Please log in."
//             ], 401);
//         }

//         $validator = Validator::make($request->all(), [
//             "fcm_token" => ["required", "string"],
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Invalid input",
//                 "errors" => $validator->errors()
//             ], 422);
//         }

//         try {

//             $existingToken = DeviceToken::where("device_token", $request->fcm_token)->first();

//             if ($existingToken) {

//                 if ($existingToken->user_id !== $user->id) {
//                     $existingToken->update(["user_id" => $user->id]);
//                 }
//             } else {

//                 DeviceToken::create([
//                     "user_id" => $user->id,
//                     "device_token" => $request->fcm_token,
//                 ]);
//             }

//             return response()->json([
//                 "success" => true,
//                 "message" => "Device token registered successfully"
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Failed to register device token",
//                 "error" => $e->getMessage()
//             ], 500);
//         }
//     }

//     public function sendToCurrentUser(Request $request)
//     {

//         $user = Auth::user();
//         if (!$user) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Unauthenticated. Please log in."
//             ], 401);
//         }

//         $validator = Validator::make($request->all(), [
//             "title" => ["required", "string"],
//             'body' => ["required", "string"],
//             'data' => ["nullable", "array"]
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Invalid input",
//                 "errors" => $validator->errors()
//             ], 422);
//         }

//         try {
//             $report = $this->firebaseService->sendToUser(
//                 $user->id,
//                 $request->title,
//                 $request->body,
//                 $request->data ?? []
//             );

//             if ($report && $report->successes()->count() > 0) {
//                 return response()->json([
//                     "success" => true,
//                     "message" => "Notification sent successfully",
//                     "successes" => $report->successes()->count(),
//                     "failures" => $report->failures()->count(),
//                 ], 200);
//             } else {
//                 return response()->json([
//                     "success" => false,
//                     "message" => "Failed to send notification",
//                     "successes" => $report ? $report->successes()->count() : 0,
//                     "failures" => $report ? $report->failures()->count() : 0,
//                 ], 500);
//             }
//         } catch (\Exception $e) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Failed to send notification",
//                 "error" => $e->getMessage()
//             ], 500);
//         }
//     }

//     public function removeDeviceToken(Request $request)
//     {

//         $user = Auth::user();
//         if (!$user) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Unauthenticated. Please log in."
//             ], 401);
//         }

//         $validator = Validator::make($request->all(), [
//             "fcm_token" => ["required", "string"]
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Invalid input",
//                 "errors" => $validator->errors()
//             ], 422);
//         }

//         try {
//             DeviceToken::where("user_id", $user->id)
//                 ->where("device_token", $request->fcm_token)
//                 ->delete();

//             return response()->json([
//                 "success" => true,
//                 "message" => "Device token removed successfully"
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 "success" => false,
//                 "message" => "Failed to remove device token",
//                 "error" => $e->getMessage()
//             ], 500);
//         }
//     }
// }
