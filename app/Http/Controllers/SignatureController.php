<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SignatureController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show the signature management page.
     */
    public function show()
    {
        $user = Auth::user();

        // Check if user has signature authority
        if (!$user->signature_authority) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have signature authority.');
        }

        $signature = $user->signature;

        return view('signature.show', compact('user', 'signature'));
    }

    /**
     * Store a new signature.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user has signature authority
        if (!$user->signature_authority) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have signature authority.');
        }

        $validated = $request->validate([
            'signature_image' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        // Process signature image
        $signatureImage = $request->file('signature_image');

        // Create an optimized version with transparent background
        $image = Image::make($signatureImage);

        // Resize if too large while maintaining aspect ratio
        if ($image->width() > 500 || $image->height() > 200) {
            $image->resize(500, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convert to PNG with transparency
        $image->encode('png');

        // Generate filename and path
        $filename = 'signature_' . $user->id . '_' . time() . '.png';
        $path = 'signatures/' . $filename;

        // Store the processed image
        Storage::put('public/' . $path, (string) $image);

        // Create or update the signature record
        $signature = $user->signature;

        if ($signature) {
            // Delete old signature image
            Storage::delete('public/' . $signature->signature_path);

            // Update existing record
            $signature->signature_path = $path;
            $signature->is_active = true;
            $signature->save();
        } else {
            // Create new signature record
            Signature::create([
                'user_id' => $user->id,
                'signature_path' => $path,
                'is_active' => true,
            ]);
        }

        return redirect()->route('signature.show')
            ->with('success', 'Signature uploaded successfully.');
    }

    /**
     * Update the signature status.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $signature = $user->signature;

        if (!$signature) {
            return redirect()->route('signature.show')
                ->with('error', 'No signature found to update.');
        }

        $signature->is_active = $validated['is_active'];
        $signature->save();

        $status = $validated['is_active'] ? 'activated' : 'deactivated';

        return redirect()->route('signature.show')
            ->with('success', "Signature {$status} successfully.");
    }
}
