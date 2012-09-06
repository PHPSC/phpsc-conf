<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use PHPSC\Conference\Application\View\Main;

use \Lcobucci\DisplayObjects\Components\SimpleList;
use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\Domain\Entity\Event;

class FeedbackList extends UIComponent
{
    /**
     * @var array
     */
    protected $talks;

    /**
     * @var string
     */
    protected $shareUrl;

    /**
     * @var int
     */
    protected $likesCount;

    /**
     * @param array $talks
     * @param string $shareUrl
     * @param int $likesCount
     */
    public function __construct(array $talks, $shareUrl, $likesCount)
    {
        $this->talks = $talks;
        $this->shareUrl = $shareUrl;
        $this->likesCount = $likesCount;

        Main::appendScript($this->getBaseUrl() . '/js/submissions.feedback.js');
    }

    /**
     * @return \Lcobucci\DisplayObjects\Components\SimpleList
     */
    public function getList()
    {
        if (isset($this->talks[0])) {
            return new SimpleList(
                'talkList',
                $this->talks,
                new FeedbackItem()
            );
        }
    }

    /**
     * @return string
     */
    public function getShareUrl()
    {
        return $this->shareUrl;
    }

    /**
     * @return number
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }
}