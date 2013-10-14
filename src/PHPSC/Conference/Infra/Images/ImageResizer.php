<?php
namespace PHPSC\Conference\Infra\Images;

use Imagick;

/**
 * @author Luís Otávio Cobucci Oblonczyk
 */
class ImageResizer
{
    /**
     * @param Imagick $image
     * @param string $path
     * @param int $maxWidth
     * @param int $maxHeight
     * @return string
     */
    public function scale(Imagick $image, $path, $maxWidth, $maxHeight)
    {
        $this->createPath($path);

        $image->resizeImage($maxWidth, $maxHeight, Imagick::FILTER_LANCZOS, 1, true);
        $image->writeImage($path);
    }

    /**
     * @param string $path
     */
    protected function createPath($path)
    {
        $dir = dirname($path);

        if (file_exists($dir)) {
            return ;
        }

        mkdir($dir, 0777, true);
    }
}
