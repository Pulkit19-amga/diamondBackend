<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs; 
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
      /**
     * Show list of contacts (HTML view).
     */
    public function index()
    {
        $contacts = ContactUs::orderBy('created_at', 'desc')->get();
        return view('admin.Contact.index', compact('contacts'));
    }

    /**
     * Send an email to a specific contact.
     */
    public function sendMail(Request $request, $id)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $contact = ContactUs::findOrFail($id);

        Mail::to($contact->email)
            ->send(new ContactUserMail($data['subject'], $data['body']));

        return response()->json([
            'status'  => 'success',
            'message' => 'Email sent to ' . $contact->email
        ]);
    }
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:20',
            'topic'    => 'nullable|string|max:255',
            'question' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = ContactUs::create($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Your question has been received. We will get in touch with you shortly.',
            'data'    => $contact
        ], 200);
    }
}