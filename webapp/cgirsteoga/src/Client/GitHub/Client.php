<?php

namespace Webapp\Client\GitHub;

use Webapp\Client\ClientInterface as HTTPClient;


class Client
{
    /**
     * @var HTTPClient
     */
    private $client;

    /**
     * Client constructor.
     * @param HTTPClient $client
     */
    public function __construct(HTTPClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $userName
     * @return string|null
     * @throws \Exception
     */
    public function getProfileLink(string $userName)
    {
        $response = $this->getUserInfo($userName);

        if ($response->isSuccessful()) {
            return $response->getOffset(UserResponse::HTML_URL);
        }

        return null;
    }

    /**
     * @param string $userName
     * @return UserResponse
     * @throws \Exception
     */
    public function getUserInfo(string $userName)
    {
        $request = new UserRequest($userName);

        $result = $this->client->get($request);

        $response = new UserResponse($result);

        if ($response->getErrorMessage()) {
            throw new \Exception(
                sprintf(
                    'Try to call endpoint: %s, and get error: %s',
                    $request->getHost(),
                    $response->getErrorMessage()
                )
            );
        }

        return $response;
    }

}
