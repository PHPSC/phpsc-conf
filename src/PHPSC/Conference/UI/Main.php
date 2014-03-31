<?php
namespace PHPSC\Conference\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;
use Lcobucci\ActionMapper2\Application;
use PHPSC\Conference\Infra\UI\Component;

class Main extends Component
{
    /**
     * @var UIComponent
     */
    protected $content;

    /**
     * @var array
     */
    protected static $scripts;

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
        return new NavigationBar();
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
