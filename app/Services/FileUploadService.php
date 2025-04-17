<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Allowed file extensions and their corresponding MIME types.
     */
    protected $allowedTypes = [
        // Documents
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

        // Images
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',

        // Other
        'txt' => 'text/plain',
    ];

    /**
     * Maximum file size in bytes (default: 10MB).
     */
    protected $maxFileSize = 10485760; // 10MB

    /**
     * Store an uploaded file securely.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The storage directory
     * @param array $allowedExtensions Optional array of allowed extensions (will use default if not provided)
     * @param int $maxSize Optional maximum file size (will use default if not provided)
     * @return string|false The file path if successful, false on failure
     */
    public function store(UploadedFile $file, string $directory, array $allowedExtensions = [], int $maxSize = null)
    {
        // Check if the file is valid
        if (!$file->isValid()) {
            return false;
        }

        // Use provided parameters or defaults
        $allowedExtensions = !empty($allowedExtensions) ? $allowedExtensions : array_keys($this->allowedTypes);
        $maxSize = $maxSize ?? $this->maxFileSize;

        // Check file extension
        $extension = $file->getClientOriginalExtension();
        if (!in_array(strtolower($extension), array_map('strtolower', $allowedExtensions))) {
            return false;
        }

        // Check file MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedTypes)) {
            return false;
        }

        // Check file size
        if ($file->getSize() > $maxSize) {
            return false;
        }

        // Generate a unique filename
        $filename = Str::uuid() . '.' . $extension;

        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Delete a file.
     *
     * @param string $path File path
     * @return bool True if deleted successfully, false otherwise
     */
    public function delete(string $path)
    {
        return Storage::delete($path);
    }

    /**
     * Set custom allowed file types.
     *
     * @param array $types Array of extensions and MIME types
     * @return $this
     */
    public function setAllowedTypes(array $types)
    {
        $this->allowedTypes = $types;
        return $this;
    }

    /**
     * Set maximum file size.
     *
     * @param int $size Maximum size in bytes
     * @return $this
     */
    public function setMaxFileSize(int $size)
    {
        $this->maxFileSize = $size;
        return $this;
    }
}
