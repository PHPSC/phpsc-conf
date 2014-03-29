<?php
namespace PHPSC\Conference\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;
use Lcobucci\ActionMapper2\Application;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\UI\Component;

class Main extends Component
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var UIComponent
     */
    protected $content;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var array
     */
    protected static $scripts;

    /**
     * @param UIComponent $content
     * @param Application $application
     * @return Main
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
     * @param UIComponent $content
     */
    public function __construct(UIComponent $content)
    {
        $this->content = $content;

        static::appendScript($this->getUrl('js/vendor/bootstrap.min.js'), true);
        static::appendScript($this->getUrl('js/vendor/jquery-1.10.2.min.js'), true);
    }

    /**
     * @param Application $application
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
     * @return UIComponent
     */
    public function renderContent()
    {
        return $this->content;
    }

    /**
     * @return NavigationBar
     */
    public function renderNavigation()
    {
        return new NavigationBar(
            $this->getCurrentEvent(),
            $this->application->getDependencyContainer()
                              ->get('authentication.service')
                              ->getLoggedUser()
        );
    }

    /**
     * @return Event
     */
    public function getCurrentEvent()
    {
        if ($this->event === null) {
            $this->event = $this->application->getDependencyContainer()
                                             ->get('event.management.service')
                                             ->findCurrentEvent();
        }

        return $this->event;
    }

    /**
     * @return Footer
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
