<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFileUpload
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('attachments')) {
            $allowedMimes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip',
            ];

            $maxSize = 5 * 1024; // 5MB in KB

            foreach ($request->file('attachments') as $file) {
                // Check file size
                if ($file->getSize() > $maxSize * 1024) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Ukuran berkas terlalu besar. Maksimal 5MB.',
                        ], 422);
                    }

                    return back()->withErrors(['attachments' => 'Ukuran berkas terlalu besar. Maksimal 5MB.'])->withInput();
                }

                // Check MIME type
                if (! in_array($file->getMimeType(), $allowedMimes)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Format berkas tidak diizinkan. Format yang didukung: JPG, PNG, GIF, WEBP, PDF, DOC, DOCX, XLS, XLSX, ZIP.',
                        ], 422);
                    }

                    return back()->withErrors(['attachments' => 'Format berkas tidak diizinkan. Format yang didukung: JPG, PNG, GIF, WEBP, PDF, DOC, DOCX, XLS, XLSX, ZIP.'])->withInput();
                }

                // Check for malicious file extensions
                $extension = strtolower($file->getClientOriginalExtension());
                $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'sh', 'php', 'phtml', 'phar'];

                if (in_array($extension, $dangerousExtensions)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Ekstensi berkas tidak diizinkan demi keamanan.',
                        ], 422);
                    }

                    return back()->withErrors(['attachments' => 'Ekstensi berkas tidak diizinkan demi keamanan.'])->withInput();
                }
            }
        }

        return $next($request);
    }
}
