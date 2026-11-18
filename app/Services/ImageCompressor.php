<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Kompres foto yang di-upload agar ukurannya kecil tapi tetap HD.
 *
 * - Sisi terpanjang diperkecil ke maksimal 1920 px (Full HD); foto yang lebih kecil tidak diperbesar.
 * - Disimpan sebagai WebP kualitas 82 (secara kasat mata sama dengan aslinya, ukuran jauh lebih kecil).
 * - Transparansi (mis. logo PNG) tetap terjaga.
 * - Rotasi foto dari HP (EXIF) dibetulkan otomatis.
 * - Jika hasil kompres justru lebih besar dari aslinya, file asli yang disimpan.
 */
class ImageCompressor
{
    public function __construct(
        private int $maxSize = 1920,
        private int $quality = 82,
    ) {}

    /** Simpan file ke disk "public" di folder $dir, kembalikan path relatifnya. */
    public function store(UploadedFile $file, string $dir, ?int $maxSize = null): string
    {
        $maxSize ??= $this->maxSize;
        $src = $this->load($file);

        // Format yang tidak bisa diolah GD (mis. SVG) disimpan apa adanya.
        if (! $src) {
            return $file->store($dir, 'public');
        }

        $src = $this->fixOrientation($src, $file);
        $img = $this->resize($src, $maxSize);

        $useWebp = function_exists('imagewebp');
        ob_start();
        if ($useWebp) {
            imagewebp($img, null, $this->quality);
        } else {
            // Cadangan bila server tanpa dukungan WebP.
            $bg = imagecreatetruecolor(imagesx($img), imagesy($img));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            imageinterlace($bg, true);
            imagejpeg($bg, null, $this->quality);
            imagedestroy($bg);
        }
        $data = ob_get_clean();
        imagedestroy($img);

        // Jangan sampai hasilnya lebih besar dari file asli (mis. foto kecil yang sudah terkompres).
        $resized = $img !== $src;
        if (! $resized && strlen($data) >= $file->getSize()) {
            return $file->store($dir, 'public');
        }

        $path = trim($dir, '/') . '/' . Str::random(40) . ($useWebp ? '.webp' : '.jpg');
        Storage::disk('public')->put($path, $data);

        return $path;
    }

    private function load(UploadedFile $file): \GdImage|false
    {
        $path = $file->getRealPath();
        $img = match (@exif_imagetype($path)) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            IMAGETYPE_BMP => @imagecreatefrombmp($path),
            default => (defined('IMAGETYPE_AVIF') && @exif_imagetype($path) === IMAGETYPE_AVIF && function_exists('imagecreatefromavif'))
                ? @imagecreatefromavif($path) : false,
        };

        if ($img && ! imageistruecolor($img)) {
            imagepalettetotruecolor($img);
        }
        if ($img) {
            imagealphablending($img, false);
            imagesavealpha($img, true);
        }

        return $img;
    }

    private function fixOrientation(\GdImage $img, UploadedFile $file): \GdImage
    {
        if (! function_exists('exif_read_data') || @exif_imagetype($file->getRealPath()) !== IMAGETYPE_JPEG) {
            return $img;
        }
        $exif = @exif_read_data($file->getRealPath());
        $angle = match ((int) ($exif['Orientation'] ?? 1)) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };
        if ($angle === 0) {
            return $img;
        }
        $rotated = imagerotate($img, $angle, 0);
        imagedestroy($img);

        return $rotated ?: $img;
    }

    private function resize(\GdImage $src, int $maxSize): \GdImage
    {
        $w = imagesx($src);
        $h = imagesy($src);
        $scale = $maxSize / max($w, $h);
        if ($scale >= 1) {
            return $src;
        }

        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($src);

        return $dst;
    }
}
