<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function uploadKyc(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'document_type' => 'required|string|in:national_id,passport,selfie,other',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $documentType = $request->input('document_type');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("kyc/{$user->id}", $filename, 'public');

        // Update user model with document path based on type
        $fieldMap = [
            'national_id' => 'nida_picture_path',
            'passport' => 'passport_picture_path',
            'selfie' => 'passport_picture_path', // You may want a separate field
        ];

        if (isset($fieldMap[$documentType])) {
            $user->update([
                $fieldMap[$documentType] => $path,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'KYC document uploaded successfully',
            'data' => [
                'file_path' => $path,
                'file_url' => Storage::url($path),
                'document_type' => $documentType,
            ],
        ]);
    }

    public function uploadLoanDocument(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'loan_id' => 'nullable|exists:loans,id',
            'document_type' => 'required|string|in:application_letter,payment_slip,standing_order,other',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("loans/{$user->id}", $filename, 'public');

        return response()->json([
            'success' => true,
            'message' => 'Loan document uploaded successfully',
            'data' => [
                'file_path' => $path,
                'file_url' => Storage::url($path),
                'document_type' => $request->input('document_type'),
            ],
        ]);
    }

    public function uploadWelfareDocument(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'welfare_id' => 'nullable|exists:social_welfares,id',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("welfare/{$user->id}", $filename, 'public');

        return response()->json([
            'success' => true,
            'message' => 'Welfare document uploaded successfully',
            'data' => [
                'file_path' => $path,
                'file_url' => Storage::url($path),
            ],
        ]);
    }

    public function uploadIssueAttachment(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'issue_id' => 'nullable|exists:issues,id',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("issues/{$user->id}", $filename, 'public');

        return response()->json([
            'success' => true,
            'message' => 'Issue attachment uploaded successfully',
            'data' => [
                'file_path' => $path,
                'file_url' => Storage::url($path),
            ],
        ]);
    }
}
