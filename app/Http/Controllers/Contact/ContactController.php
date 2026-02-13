<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Handle landing page contact form submissions.
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // TODO: Integrate with mail, CRM, or database logging as needed.

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out. We will contact you soon.',
            'data' => [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
            ],
        ]);
    }
}

