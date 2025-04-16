<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LetterController extends Controller
{
    // app/Http/Controllers/LetterController.php
    public function store(StoreLetterRequest $request)
    {
        $this->authorize('create', Letter::class);

        $letter = new Letter($request->validated());
        $letter->created_by = auth()->id();

        // Generate nomor surat otomatis
        $format = LetterNumberFormat::where('department_id', $request->department_id)
            ->where('is_active', true)
            ->first();

        if ($format) {
            $number = $format->next_number;
            $pattern = $format->format_pattern;

            // Replace placeholders
            $date = now();
            $pattern = str_replace('{YEAR}', $date->format('Y'), $pattern);
            $pattern = str_replace('{MONTH}', $date->format('m'), $pattern);
            $pattern = str_replace('{NUMBER}', str_pad($number, 3, '0', STR_PAD_LEFT), $pattern);
            $pattern = str_replace('{DEPT}', $letter->department->slug, $pattern);

            $letter->letter_number = $pattern;

            // Increment nomor surat
            $format->next_number++;
            $format->save();
        }

        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Surat berhasil dibuat');
    }

    public function sign(Letter $letter, Request $request)
    {
        $this->authorize('sign', $letter);

        // Validasi ketersediaan tanda tangan
        $signature = auth()->user()->signature;

        if (!$signature || !$signature->is_active) {
            return back()->with('error', 'Anda belum memiliki tanda tangan digital');
        }

        // Tanda tangani surat
        $letter->signed_by = auth()->id();
        $letter->signing_date = now();
        $letter->status = 'signed';
        $letter->save();

        // Generate versi PDF dengan tanda tangan
        $pdf = PDF::loadView('letters.pdf', ['letter' => $letter]);
        $filename = 'letter_' . $letter->id . '_signed.pdf';
        $path = 'letters/' . $filename;

        Storage::put('public/' . $path, $pdf->output());
        $letter->document_path = $path;
        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Surat berhasil ditandatangani');
    }
}
