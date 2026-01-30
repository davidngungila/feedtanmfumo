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
     * Get font as base64 for PDF
     */
    public static function getFontBase64(string $fontFile): ?string
    {
        $fontPath = public_path("font/{$fontFile}");

        if (file_exists($fontPath)) {
            $fontData = file_get_contents($fontPath);

            return 'data:font/truetype;charset=utf-8;base64,'.base64_encode($fontData);
        }

        return null;
    }

    /**
     * Generate PDF with standard header
     */
    public static function generatePdf(string $view, array $data = [], ?string $filename = null): \Barryvdh\DomPDF\PDF
    {
        // Add header image to data
        $data['headerBase64'] = self::getHeaderImageBase64();

        // Add font base64 data
        $data['fontRegular'] = self::getFontBase64('Quicksand-Regular.ttf');
        $data['fontMedium'] = self::getFontBase64('Quicksand-Medium.ttf');
        $data['fontSemiBold'] = self::getFontBase64('Quicksand-SemiBold.ttf');
        $data['fontBold'] = self::getFontBase64('Quicksand-Bold.ttf');

        // Add generated timestamp if not provided
        if (! isset($data['generatedAt'])) {
            $data['generatedAt'] = now()->format('Y-m-d H:i:s');
        }

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf;
    }

    /**
     * Download PDF with standard header
     */
    public static function downloadPdf(string $view, array $data = [], ?string $filename = null): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (! $filename) {
            $filename = 'document-'.date('Y-m-d-His').'.pdf';
        }

        return self::generatePdf($view, $data, $filename)->download($filename);
    }
}
