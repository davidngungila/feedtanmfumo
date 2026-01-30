<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfHelper
{
    /**
     * Get header image as base64 for PDF
     */
    public static function getHeaderImageBase64(): ?string
    {
        $headerPath = public_path('header-mfumo.png');

        if (file_exists($headerPath)) {
            $headerData = file_get_contents($headerPath);

            return 'data:image/png;base64,'.base64_encode($headerData);
        }

        return null;
    }

    /**
     * Generate PDF with standard header
     * 
     * @param string $view The view to render
     * @param array $data Data to pass to the view
     * @param string|null $filename Optional filename
     * @param string|array $paperSize Paper size ('a4', 'letter', or [width, height] in points). Default: 'a4'
     * @param string $orientation Paper orientation ('portrait' or 'landscape'). Default: 'portrait'
     */
    public static function generatePdf(string $view, array $data = [], ?string $filename = null, $paperSize = 'a4', string $orientation = 'portrait'): \Barryvdh\DomPDF\PDF
    {
        // Add header image to data
        $data['headerBase64'] = self::getHeaderImageBase64();

        // Add generated timestamp if not provided
        if (! isset($data['generatedAt'])) {
            $data['generatedAt'] = now()->format('Y-m-d H:i:s');
        }

        $pdf = Pdf::loadView($view, $data)
            ->setPaper($paperSize, $orientation)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf;
    }

    /**
     * Download PDF with standard header
     * 
     * @param string $view The view to render
     * @param array $data Data to pass to the view
     * @param string|null $filename Optional filename
     * @param string|array $paperSize Paper size ('a4', 'letter', or [width, height] in points). Default: 'a4'
     * @param string $orientation Paper orientation ('portrait' or 'landscape'). Default: 'portrait'
     */
    public static function downloadPdf(string $view, array $data = [], ?string $filename = null, $paperSize = 'a4', string $orientation = 'portrait')
    {
        if (! $filename) {
            $filename = 'document-'.date('Y-m-d-His').'.pdf';
        }

        return self::generatePdf($view, $data, $filename, $paperSize, $orientation)->download($filename);
    }
}
