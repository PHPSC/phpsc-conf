<?php
namespace PHPSC\Conference\Infra\Images;

use Imagick;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class ImageFactory
{
    /**
     * @param string $filename
     * @return Imagick
     */
    public function createFromFile($filename)
    {
        return new Imagick($filename);
    }

    /**
     * @param resource $resource
     * @return Imagick
     */
    public function createFromResource($resource)
    {
        $image = new Imagick();
        $image->readImageFile($resource);

        return $image;
    }
}
