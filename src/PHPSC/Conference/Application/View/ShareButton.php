<?php
namespace PHPSC\Conference\Application\View;

use Lcobucci\DisplayObjects\Core\UIComponent;

class ShareButton extends UIComponent
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $buttonClass;

    /**
     * @var string
     */
    protected $containerClass;

    /**
     * @var string
     */
    protected $containerId;

    /**
     * @param string $title
     * @param string $message
     * @param string $url
     * @param string $user
     * @param string $buttonClass
     * @param string $containerClass
     * @param string $containerId
     */
    public function __construct(
        $title,
        $message,
        $url,
        $user,
        $buttonClass = 'btn-info',
        $containerClass = 'pull-right',
        $containerId = 'shareButtons'
    ) {
        $this->title = urlencode($title);
        $this->message = urlencode($message);
        $this->url = urlencode($url);
        $this->user = urlencode($user);
        $this->buttonClass = $buttonClass;
        $this->containerClass = $containerClass;
        $this->containerId = $containerId;
    }

    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return $this->buttonClass;
    }

    /**
     * @return string
     */
    protected function getContainerClass()
    {
        return $this->containerClass;
    }

    /**
     * @return string
     */
    protected function getContainerId()
    {
        return $this->containerId;
    }

    protected function getLinks()
    {
        return array(
            'Twitter' => $this->getTwitterLink(),
            'Facebook' => $this->getFacebookLink(),
            'LinkedIn' => $this->getLinkedInLink(),
            'Google+' => $this->getGooglePlusLink()
        );
    }

    protected function getTwitterLink()
    {
        return sprintf(
            'https://twitter.com/intent/tweet?&text=%s&url=%s&via=%s',
            $this->message,
            $this->url,
            $this->user
        );
    }

    protected function getFacebookLink()
    {
        return sprintf(
            'https://www.facebook.com/sharer/sharer.php?u=%s',
            $this->url
        );
    }

    protected function getGooglePlusLink()
    {
        return sprintf(
            'https://plus.google.com/share?url=%s&hl=pt-BR',
            $this->url
        );
    }

    protected function getLinkedInLink()
    {
        return sprintf(
            'http://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s&summary=%s',
            $this->url,
            $this->title,
            $this->message
        );
    }
}
