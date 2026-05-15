<?php

namespace App\Libraries;

class Watermark
{
    private $watermarkText;
    private $fontSize;
    private $opacity;

    public function __construct()
    {
        $this->watermarkText = 'AI Art Store © ' . date('Y');
        $this->fontSize = 30;
        $this->opacity = 50;
    }

    public function apply($sourcePath, $destinationPath = null)
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $info = getimagesize($sourcePath);
        if (!$info) return false;

        $destinationPath = $destinationPath ?? $sourcePath;

        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($sourcePath);
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $textColor = imagecolorallocatealpha($image, 255, 255, 255, $this->opacity);
        $borderColor = imagecolorallocatealpha($image, 0, 0, 0, $this->opacity);

        $fontFile = __DIR__ . '/../Views/assets/fonts/arial.ttf';
        if (!file_exists($fontFile)) {
            $fontFile = realpath(__DIR__ . '/../../system/Debug/Toolbar/fonts/DejaVuSans.ttf') ?: null;
        }

        if ($fontFile) {
            $bbox = imagettfbbox($this->fontSize, 45, $fontFile, $this->watermarkText);
            $textWidth = $bbox[2] - $bbox[0];
            $textHeight = $bbox[1] - $bbox[7];

            $x = $width - $textWidth - 20;
            $y = $height - 20;

            imagettftext($image, $this->fontSize, 45, $x + 1, $y + 1, $borderColor, $fontFile, $this->watermarkText);
            imagettftext($image, $this->fontSize, 45, $x, $y, $textColor, $fontFile, $this->watermarkText);

            $this->addTiledWatermark($image, $width, $height, $textColor, $fontFile);
        } else {
            $this->addStringWatermark($image, $width, $height, $textColor);
        }

        $result = false;
        switch ($info['mime']) {
            case 'image/jpeg':
                $result = imagejpeg($image, $destinationPath, 90);
                break;
            case 'image/png':
                $result = imagepng($image, $destinationPath, 9);
                break;
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    $result = imagewebp($image, $destinationPath, 90);
                }
                break;
        }

        imagedestroy($image);
        return $result;
    }

    private function addTiledWatermark($image, $width, $height, $color, $fontFile)
    {
        $smallSize = 12;
        $text = 'AI Art Store';
        $bbox = imagettfbbox($smallSize, 0, $fontFile, $text);
        $tw = $bbox[2] - $bbox[0] + 40;
        $th = $bbox[1] - $bbox[7] + 20;

        for ($x = 0; $x < $width; $x += $tw) {
            for ($y = 0; $y < $height; $y += $th) {
                imagettftext($image, $smallSize, 0, $x, $y, $color, $fontFile, $text);
            }
        }
    }

    private function addStringWatermark($image, $width, $height, $color)
    {
        $text = $this->watermarkText;
        $fontSize = 5;
        $tw = imagefontwidth($fontSize) * strlen($text);
        $th = imagefontheight($fontSize);
        $x = $width - $tw - 10;
        $y = $height - 10;
        imagestring($image, $fontSize, $x, $y, $text, $color);

        for ($px = 0; $px < $width; $px += 150) {
            for ($py = 0; $py < $height; $py += 30) {
                imagestring($image, 2, $px, $py, 'AI Art Store', $color);
            }
        }
    }
}
