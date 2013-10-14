<?php
namespace PHPSC\Conference\Infra\Images;

use Imagick;

class ImageValidator
{
    /**
     * @param Imagick $image
     * @return boolean
     */
    public function isTransparentPng(Imagick $image)
    {
        $info = $image->identifyImage();

        if (strpos($info['format'], 'PNG') === false) {
            return false;
        }

        if ($image->getImageAlphaChannel() !== Imagick::ALPHACHANNEL_ACTIVATE) {
            return false;
        }

        return true;
    }
}
