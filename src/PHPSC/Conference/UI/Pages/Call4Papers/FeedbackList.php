<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use Lcobucci\DisplayObjects\Components\SimpleList;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\ShareButton;

class FeedbackList extends Component
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

        Main::appendScript($this->getUrl('js/submissions.feedback.js'));
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

    public function getShareButton()
    {
        return new ShareButton(
            'Minha avaliação sobre as palestras',
            'Gostei de algumas submissões do #phpscConf. Dê sua opinião também!',
            $this->shareUrl,
            'PHP_SC',
            'btn-info',
            !$this->likesCount ? 'pull-right hide' : 'pull-right',
            'shareFeedback'
        );
    }
}
