<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Contracts\EventDispatcher\Event;

class LPJController extends Controller
{
    // app/Http/Controllers/LPJController.php
    public function create(Event $event, LPJTemplate $template)
    {
        $this->authorize('create', [LPJ::class, $event]);

        // Pastikan event sudah selesai
        if ($event->end_date > now()) {
            return back()->with('error', 'Event belum selesai');
        }

        // Buat LPJ dengan struktur dari template
        $lpj = new LPJ();
        $lpj->title = $event->title . ' - LPJ';
        $lpj->event_id = $event->id;
        $lpj->template_id = $template->id;
        $lpj->content = $template->structure; // Struktur kosong
        $lpj->created_by = auth()->id();
        $lpj->save();

        return redirect()->route('lpjs.edit', $lpj);
    }

    public function generatePDF(LPJ $lpj)
    {
        $this->authorize('view', $lpj);

        $data = [
            'lpj' => $lpj,
            'event' => $lpj->event,
            'creator' => $lpj->creator,
            'template' => $lpj->template
        ];

        $pdf = PDF::loadView('lpjs.pdf', $data);

        return $pdf->download($lpj->title . '.pdf');
    }
}
