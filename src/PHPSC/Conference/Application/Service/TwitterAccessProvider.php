<?php
namespace PHPSC\Conference\Application\Service;

use \Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Abraham\TwitterOAuth\OAuth\OAuthConsumer;
use \Abraham\TwitterOAuth\OAuth\OAuthToken;
use \Abraham\TwitterOAuth\TwitterClient;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TwitterAccessProvider
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \Abraham\TwitterOAuth\OAuth\OAuthConsumer
     */
    protected $consumer;

    /**
     * @var \Abraham\TwitterOAuth\OAuth\OAuthToken
     */
    protected $token;

    /**
     * @var \Abraham\TwitterOAuth\TwitterClient
     */
    protected $client;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $callbackUrl
     */
    public function __construct(
        SessionInterface $session,
        $consumerKey,
        $consumerSecret,
        $callbackUrl
    ) {
        $this->session = $session;
        $this->consumer = new OAuthConsumer(
            $consumerKey,
            $consumerSecret,
            $callbackUrl
        );

        $this->createToken();
    }

    /**
     * Cria o token baseado nos dados da sessão
     */
    protected function createToken()
    {
        if ($auth = $this->session->get('authToken')) {
            $this->token = new OAuthToken($auth['token'], $auth['secret']);
        }
    }

    /**
     * @return \Abraham\TwitterOAuth\TwitterClient
     */
    public function getClient()
    {
        if ($this->client === null) {
            $this->client = new TwitterClient($this->consumer, $this->token);
        }

        return $this->client;
    }

    /**
     * Remove os dados da sessão
     */
    public function logoff()
    {
        $this->session->invalidate();
        $this->token = null;
    }

    /**
     * @return string
     * @throws \PHPSC\Conference\Application\Service\TwitterConnectionException
     */
    public function redirectToLogin()
    {
        $redirectPath = $this->session->get('redirectTo');
        $this->logoff();

        if ($redirectPath) {
            $this->session->set('redirectTo', $redirectPath);
        }

        $client = $this->getClient();
        $token = $client->getRequestToken($this->consumer->callbackUrl);

        if ($client->lastStatusCode() == 200) {
            $this->session->set(
                'authToken',
                array(
                    'token' => $token['oauth_token'],
                    'secret' => $token['oauth_token_secret']
                )
            );

            return $client->getAuthorizeURL($token);
        }

        throw new TwitterConnectionException(
            'Não foi possível conectar ao twitter'
        );
    }

    /**
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @return boolean
     * @throws \PHPSC\Conference\Application\Service\TwitterConnectionException
     */
    public function callback($oauthToken, $oauthVerifier)
    {
        if (is_null($this->token) || $oauthToken != $this->token->key) {
            throw new TwitterConnectionException(
                'Token inválido'
            );
        }

        $client = $this->getClient();
        $token = $client->getAccessToken($oauthVerifier);

        if ($client->lastStatusCode() == 200) {
            $this->session->set(
                'authToken',
                array(
                    'token' => $token['oauth_token'],
                    'secret' => $token['oauth_token_secret']
                )
            );

            return true;
        }

        throw new TwitterConnectionException(
            'Não foi possível conectar ao twitter'
        );
    }

    /**
     * @return \stdClass
     */
    public function getLoggedUser()
    {
        if ($this->token) {
            $client = $this->getClient();
            $data = $client->verifyCredentials(false, true);

            if ($client->lastStatusCode() == 200) {
                return $data;
            }
        }
    }
}