<?php
namespace Lcobucci\ActionMapper2\Http;

class Request extends \Symfony\Component\HttpFoundation\Request
{
    /**
     * @var string
     */
    protected $requestedPath;

    /**
     * @param string $requestedPath
     */
    public function setRequestedPath($requestedPath)
    {
        $this->requestedPath = $requestedPath;
    }

    /**
     * @return string
     */
    public function getRequestedPath()
    {
        $path = $this->requestedPath ?: $this->getPathInfo();

        if ($path != '/') {
            $path = rtrim($path, '/');
        }

        return $path;
    }
}