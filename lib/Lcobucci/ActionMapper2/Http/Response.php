<?php
namespace Lcobucci\ActionMapper2\Http;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    /**
     * @param string $content
     */
    public function appendContent($content)
    {
        return $this->setContent(
            $this->getContent() . $content
        );
    }

    /**
     * @param string $contentType
     * @param string $charset
     * @return \Lcobucci\ActionMapper2\Http\Response
     */
    public function setContentType($contentType, $charset = null)
    {
        if ($charset === null) {
            $charset = $this->charset ?: 'UTF-8';
        }

        $this->headers->set(
            'Content-Type',
            $contentType . '; charset=' . $charset
        );

        return $this;
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        $this->headers->set('Location', $url);
    }

    /**
     * @see \Symfony\Component\HttpFoundation\Response::send()
     */
    public function send()
    {
        parent::send();

        $this->terminateRequest();
    }

    /**
     * Finishes the response
     */
    protected function terminateRequest()
    {
        exit();
    }
}