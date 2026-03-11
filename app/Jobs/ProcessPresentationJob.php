<?php

namespace App\Jobs;

use App\Events\PresentationFailed;
use App\Events\PresentationReady;
use App\Models\Presentation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessPresentationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Presentation $presentation)
    {
        $this->onQueue('presentations');
    }

    public function handle(): void
    {
        try {
            $originalPath = Storage::path($this->presentation->file_path);
            $workDir = storage_path('app/temp/presentation_' . $this->presentation->id);

            // Create temporary working directory
            File::makeDirectory($workDir, 0755, true, true);

            // Step 1: Convert PPTX to PDF using LibreOffice
            $pdfPath = $this->convertToPdf($originalPath, $workDir);

            // Step 2: Get page count
            $pageCount = $this->getPdfPageCount($pdfPath);
            $this->presentation->update(['total_slides' => $pageCount]);

            // Step 3: Convert PDF pages to PNG images
            $this->convertPdfToImages($pdfPath, $pageCount, $workDir);

            // Step 4: Mark as ready and broadcast
            $this->presentation->markAsReady();
            broadcast(new PresentationReady($this->presentation));

            // Clean up temp directory
            File::deleteDirectory($workDir);

        } catch (\Exception $e) {
            Log::error('Presentation processing failed', [
                'presentation_id' => $this->presentation->id,
                'error' => $e->getMessage(),
            ]);

            $this->presentation->markAsFailed($e->getMessage());
            broadcast(new PresentationFailed($this->presentation, $e->getMessage()));

            // Clean up temp directory on failure
            if (isset($workDir) && File::exists($workDir)) {
                File::deleteDirectory($workDir);
            }

            throw $e;
        }
    }

    private function convertToPdf(string $inputPath, string $workDir): string
    {
        $command = sprintf(
            'soffice --headless --convert-to pdf --outdir %s %s 2>&1',
            escapeshellarg($workDir),
            escapeshellarg($inputPath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('LibreOffice conversion failed: ' . implode("\n", $output));
        }

        $pdfFiles = File::glob($workDir . '/*.pdf');
        if (empty($pdfFiles)) {
            throw new \Exception('PDF file was not created');
        }

        return $pdfFiles[0];
    }

    private function getPdfPageCount(string $pdfPath): int
    {
        $command = sprintf('pdfinfo %s | grep Pages: | awk \'{print $2}\'', escapeshellarg($pdfPath));
        $pageCount = trim(shell_exec($command));

        if (!is_numeric($pageCount)) {
            throw new \Exception('Could not determine PDF page count');
        }

        return (int) $pageCount;
    }

    private function convertPdfToImages(string $pdfPath, int $pageCount, string $workDir): void
    {
        $presentationDir = 'presentations/' . $this->presentation->room_id;
        $slidesDir = $presentationDir . '/slides';

        Storage::makeDirectory($slidesDir);

        for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
            // Convert single page to PNG using pdftoppm
            $outputPrefix = $workDir . '/page_' . $pageNum;
            $command = sprintf(
                'pdftoppm -png -f %d -l %d -scale-to 1920 %s %s 2>&1',
                $pageNum,
                $pageNum,
                escapeshellarg($pdfPath),
                escapeshellarg($outputPrefix)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception('Image conversion failed for page ' . $pageNum);
            }

            // Find generated PNG file
            $pngFiles = File::glob($outputPrefix . '*.png');
            if (empty($pngFiles)) {
                throw new \Exception('PNG file not created for page ' . $pageNum);
            }

            $tempPngPath = $pngFiles[0];

            // Create full-size and thumbnail versions
            $slideId = $this->presentation->id . '_' . $pageNum;
            $fullPath = $slidesDir . '/' . $slideId . '_full.png';
            $thumbPath = $slidesDir . '/' . $slideId . '_thumb.jpg';

            // Move full-size image
            Storage::put($fullPath, File::get($tempPngPath));

            // Create thumbnail
            $this->createThumbnail($tempPngPath, Storage::path($thumbPath));

            // Save slide record
            $this->presentation->slides()->create([
                'slide_number' => $pageNum,
                'image_path' => $fullPath,
                'thumbnail_path' => $thumbPath,
            ]);

            // Clean up temp PNG
            File::delete($tempPngPath);
        }
    }

    private function createThumbnail(string $sourcePath, string $destPath): void
    {
        $command = sprintf(
            'convert %s -resize 320x180 -quality 85 %s 2>&1',
            escapeshellarg($sourcePath),
            escapeshellarg($destPath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('Thumbnail creation failed: ' . implode("\n", $output));
        }
    }
}
