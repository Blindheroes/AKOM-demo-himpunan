<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $letter->title }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: {{ $letter->template->format['font_family'] ?? 'Arial, sans-serif' }};
            font-size: {{ $letter->template->format['font_size'] ?? '12pt' }};
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .letterhead {
            text-align: center;
            margin-bottom: 30px;
        }
        .letterhead img {
            max-height: 100px;
        }
        .letter-number {
            margin-bottom: 20px;
        }
        .date {
            margin-bottom: 30px;
        }
        .recipient {
            margin-bottom: 30px;
        }
        .regarding {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .content {
            margin-bottom: 40px;
            text-align: justify;
        }
        .signature {
            margin-top: 50px;
        }
        .signature img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .signer-name {
            font-weight: bold;
            margin-bottom: 0;
        }
        .signer-position {
            margin-top: 0;
        }
        .attachment {
            margin-top: 40px;
        }
        .footer {
            margin-top: 50px;
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Letterhead -->
    <div class="letterhead">
        @if($letter->template->header_image_path)
        <img src="{{ storage_path('app/' . $letter->template->header_image_path) }}" alt="Letterhead">
        @else
        <h1>HIMATEKOM</h1>
        <p>Student Association of Computer Engineering</p>
        @endif
    </div>

    <!-- Letter Number -->
    <div class="letter-number">
        <p>No: {{ $letter->letter_number ?? 'DRAFT' }}</p>
    </div>

    <!-- Date -->
    <div class="date">
        <p>{{ $letter->date->format('d F Y') }}</p>
    </div>

    <!-- Recipient -->
    <div class="recipient">
        <p>To:<br>
        {{ $letter->recipient }}<br>
        @if($letter->recipient_position)
        {{ $letter->recipient_position }}<br>
        @endif
        @if($letter->recipient_institution)
        {{ $letter->recipient_institution }}
        @endif
        </p>
    </div>

    <!-- Regarding -->
    <div class="regarding">
        <p>Re: {{ $letter->regarding }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        {!! nl2br(e($letter->content)) !!}
    </div>

    <!-- Signature -->
    <div class="signature">
        <p>{{ $letter->department->name ?? 'HIMATEKOM' }}, {{ $letter->date->format('d F Y') }}</p>
        
        @if(isset($signature) && $letter->status !== 'draft')
        <img src="{{ storage_path('app/public/' . $signature->signature_path) }}" alt="Digital Signature">
        @endif
        
        <p class="signer-name">{{ optional($letter->signer)->name ?? '[NAME]' }}</p>
        <p class="signer-position">{{ optional($letter->signer)->position ?? '[POSITION]' }}</p>
    </div>

    <!-- Attachment -->
    @if($letter->attachment)
    <div class="attachment">
        <p><strong>Attachments:</strong></p>
        {!! nl2br(e($letter->attachment)) !!}
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        @if($letter->template->footer_content)
        {!! $letter->template->footer_content !!}
        @else
        <p>HIMATEKOM - {{ now()->format('Y') }}</p>
        @endif
    </div>
</body>
</html>