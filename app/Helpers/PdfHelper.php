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
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        // Handle custom paper size
        if (is_array($paperSize) && count($paperSize) >= 2) {
            // Custom size: [width, height] in points
            // Pass array directly to DomPDF - it accepts float[] format
            $pdf->setPaper($paperSize, $orientation);
        } elseif (is_string($paperSize) && str_contains($paperSize, ',')) {
            // Already in string format "width,height"
            $pdf->setPaper($paperSize, $orientation);
        } else {
            // Standard size: string like 'a4', 'letter', etc.
            $pdf->setPaper($paperSize, $orientation);
        }

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
