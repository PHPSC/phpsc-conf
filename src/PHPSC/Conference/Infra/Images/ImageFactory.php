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
        if ($this->isImagick2Dot3()) {
            return $this->createFromBlob(stream_get_contents($resource));
        }

        $image = new Imagick();
        $image->readImageFile($resource);

        return $image;
    }

    /**
     * @param string $content
     * @return Imagick
     */
    public function createFromBlob($content)
    {
        $image = new Imagick();
        $image->readImageBlob($content);

        return $image;
    }

    /**
     * The imagick module version 2.3.0 has a bug when loading PNGs from the resource
     *
     * @link https://bugs.php.net/bug.php?id=58948
     * @return boolean
     */
    protected function isImagick2Dot3()
    {
        $version = exec('php -i | grep "imagick module version"');
        $version = substr($version, strpos($version, '=>') + 3);

        return $version == '2.3.0';
    }
}
