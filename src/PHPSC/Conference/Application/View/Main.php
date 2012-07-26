<?php
namespace PHPSC\Conference\Application\View;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\ActionMapper2\Application;

class Main extends UIComponent
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Lcobucci\ActionMapper2\Application
     */
    protected $application;

    /**
     * @var \Lcobucci\DisplayObjects\Core\UIComponent
     */
    protected $content;

    /**
     * @var array
     */
    protected static $scripts;

    /**
     * @param \Lcobucci\DisplayObjects\Core\UIComponent $content
     * @param \Lcobucci\ActionMapper2\Application $application
     * @return \PHPSC\Conference\Application\View\Main
     */
    public static function create(
        UIComponent $content,
        Application $application,
        $description = ''
    ) {
        $component = new static($content);
        $component->setApplication($application);
        $component->setDescription($description);

        return $component;
    }

    /**
     * @param \Lcobucci\DisplayObjects\Core\UIComponent $content
     */
    public function __construct(UIComponent $content)
    {
        $this->content = $content;

        static::appendScript($this->getBaseUrl() . '/js/bootstrap.min.js', true);
        static::appendScript($this->getBaseUrl() . '/js/jquery-1.7.2.min.js', true);
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \Lcobucci\DisplayObjects\Core\UIComponent
     */
    public function renderContent()
    {
        return $this->content;
    }

    /**
     * @return \PHPSC\Conference\Application\View\NavigationBar
     */
    public function renderNavigation()
    {
        return new NavigationBar($this->application);
    }

    /**
     * @return \PHPSC\Conference\Application\View\Sponsors
     */
    public function renderSponsors()
    {
        return new Sponsors();
    }

    /**
     * @return \PHPSC\Conference\Application\View\Footer
     */
    public function renderFooter()
    {
        return new Footer();
    }

    /**
     * @param string $url
     */
    public static function appendScript($url, $prepend = false)
    {
        if (static::$scripts === null) {
            static::$scripts = array();
        }

        if (!in_array($url, static::$scripts)) {
            if ($prepend) {
                array_unshift(static::$scripts, $url);
                return ;
            }

            static::$scripts[] = $url;
        }
    }

    /**
     * @return array
     */
    public function getScripts()
    {
        return static::$scripts;
    }
}