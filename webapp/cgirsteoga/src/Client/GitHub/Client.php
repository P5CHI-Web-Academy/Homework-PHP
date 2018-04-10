<?php

namespace Webapp\Client\GitHub;

use Webapp\Client\ClientInterface as HTTPClient;
use Webapp\Client\RequestInterface;


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
                \sprintf(
                    'Try to call endpoint: %s, and get error: %s',
                    $request->getHost(),
                    $response->getErrorMessage()
                )
            );
        }

        return $response;
    }

    /**
     * @param string $userName
     * @return Response
     * @throws \Exception
     */
    public function getUserRepos(string $userName)
    {
        $request = new UserReposRequest($userName);

        return $this->get($request);
    }

    /**
     * @param string $userName
     * @param string $repoName
     * @return Response
     * @throws \Exception
     */
    public function getRepoCommits(string $userName, string $repoName)
    {
        $request = new RepoRequest($userName, $repoName, RepoRequest::COMMITS);

        return $this->get($request);
    }

    /**
     * @param string $userName
     * @param string $repoName
     * @return Response
     * @throws \Exception
     */
    public function getRepoContributors(string $userName, string $repoName)
    {
        $request = new RepoRequest($userName, $repoName, RepoRequest::CONTRIBUTORS);

        return $this->get($request);
    }

    /**
     * @param RequestInterface $request
     * @return Response
     * @throws \Exception
     */
    protected function get(RequestInterface $request)
    {
        $result = $this->client->get($request);
        $response = new Response($result);

        if ($response->getErrorMessage()) {
            throw new \Exception(
                \sprintf(
                    'Try to call endpoint: %s, and get error: %s',
                    $request->getHost(),
                    $response->getErrorMessage()
                )
            );
        }

        return $response;
    }
}
