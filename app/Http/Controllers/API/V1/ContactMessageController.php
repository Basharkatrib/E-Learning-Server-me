<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Store a new contact message.
     */
    public function store(StoreContactMessageRequest $request)
    {
        try {
            $message = ContactMessage::create($request->validated());

            return response()->json([
                'message' => 'Message sent successfully',
                'data' => $message
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all messages (admin only).
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $messages = ContactMessage::latest()->paginate(10);

        return response()->json($messages);
    }

    /**
     * Mark message as read (admin only).
     */
    public function markAsRead(Request $request, ContactMessage $message)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->update(['is_read' => true]);

        return response()->json([
            'message' => 'Message marked as read',
            'data' => $message
        ]);
    }

    /**
     * Delete a message (admin only).
     */
    public function destroy(Request $request, ContactMessage $message)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }
} 