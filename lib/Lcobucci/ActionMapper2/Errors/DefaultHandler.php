<?php
namespace Lcobucci\ActionMapper2\Errors;

use \Lcobucci\ActionMapper2\Http\Request;
use \Lcobucci\ActionMapper2\Http\Response;

class DefaultHandler extends ErrorHandler
{
    /**
     * @var string
     */
    private $content;

    /**
     * Class constructor
     *
     * @param string $templateFile
     */
    public function __construct($templateFile = null)
    {
        parent::__construct();

        $this->content = file_get_contents(
                $templateFile !== null && file_exists($templateFile)
                ? $templateFile
                : __DIR__ . '/ErrorPage.phtml'
        );
    }

    /**
     * @see \Lcobucci\ActionMapper2\Errors\ErrorHandler::getErrorContent()
     */
    protected function getErrorContent(
        Request $request,
        Response $response,
        HttpException $error
    ) {
        return str_replace(
            array('{title}', '{statusCode}', '{message}', '{trace}'),
            array(
                'An error has occurred...',
                $error->getStatusCode(),
                $error->getMessage(),
                $error
            ),
            $this->content
        );
    }
}