<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Laravel\Passport\Http\Controllers\ConvertsPsrResponses;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

trait HasPassportPasswordGrant
{
    use ConvertsPsrResponses;

    /**
     * Generate Password Grant Token
     *
     * @param  string  $username
     * @param  string  $password
     * @param  array  $scopes
     * @return \stdClass
     *
     * @throws \JsonException
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public static function generatePasswordGrantToken(string $username, string $password, array $scopes = ['*']): \stdClass
    {
        $config = (new static)->getPasswordGrantConfig();

        $requestData = [
            'grant_type' => 'password',
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'username' => $username,
            'password' => $password,
            'scope' => $scopes,
        ];

        $serverRequest = app(ServerRequestInterface::class)->withParsedBody($requestData);

        $response = (new static)->convertResponse(
            app(AuthorizationServer::class)->respondToAccessTokenRequest($serverRequest, new Psr7Response())
        );

        return json_decode($response->content(), false, 512, JSON_THROW_ON_ERROR);
    }

    protected function getPasswordGrantConfig(): array
    {
        $table = $this->getTable();

        return config("auth.providers.{$table}.passport", []);
    }
}
