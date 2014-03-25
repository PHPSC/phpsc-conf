<?php
namespace PHPSC\Conference\Application\Service;

use Imagick;
use Lcobucci\ActionMapper2\Http\Request;
use Lcobucci\ActionMapper2\Http\Response;
use PHPSC\Conference\Infra\Images\ImageFactory;
use PHPSC\Conference\Infra\Images\ImageResizer;
use PHPSC\Conference\Domain\Entity\Logo;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class ImageRenderingService
{
    /**
     * @var ImageFactory
     */
    private $factory;

    /**
     * @var ImageResizer
     */
    private $resizer;

    /**
     * @var string
     */
    private $appDir;

    /**
     * @param ImageFactory $factory
     * @param ImageResizer $resizer
     * @param string $appDir
     */
    public function __construct(ImageFactory $factory, ImageResizer $resizer, $appDir)
    {
        $this->factory = $factory;
        $this->resizer = $resizer;
        $this->appDir = $appDir;
    }

    /**
     * @param resource $logo
     * @param string $id
     * @param int $width
     * @param int $height
     * @param Request $request
     * @param Response $response
     */
    public function resize(
        Logo $logo,
        $width,
        $height,
        Request $request,
        Response $response
    ) {
        $path = $this->getPath('logo_' . $logo->getId(), $width, $height);

        $response->setPublic();
        $response->setContentType($logo->getMimeType());
        $response->setLastModified($logo->getCreatedAt());

        if ($response->isNotModified($request)) {
            return ;
        }

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        $image = $this->factory->createFromResource($logo->getImage());

        return $this->getImageContent(
            $image,
            $path,
            $width,
            $height,
            array($this->resizer, 'scale')
        );
    }

    /**
     * @param Imagick $image
     * @param string $path
     * @param int $width
     * @param int $height
     * @param callback $callback
     * @return string
     */
    protected function getImageContent(
        Imagick $image,
        $path,
        $width,
        $height,
        $callback
    ) {
        if ($width !== null && $height !== null) {
            call_user_func($callback, $image, $path, $width, $height);
        }

        return $image->getImageBlob();
    }

    /**
     * @param string $filename
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getPath($filename, $width, $height)
    {
        return sprintf(
            '%s/tmp/images/%s/%s/%s',
            $this->appDir,
            (int) $width,
            (int) $height,
            md5($filename)
        );
    }
}
