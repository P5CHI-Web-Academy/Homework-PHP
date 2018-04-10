<?php

namespace Webapp\Service;

use Webapp\Client\GitHub\Client;

class UserReposInfoProvider
{
    const USER_REPOS = 'user_repos';
    const USER_PROFILE_LINK = 'user_github_link';
    const DATE_FORMAT = 'd-M-Y g:i A';

    /**
     * @var Client
     */
    private $client;
    /**
     * @var Session
     */
    private $session;

    /**
     * UserReposInfoProvider constructor.
     * @param Client $client
     * @param Session $session
     */
    public function __construct(Client $client, Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    /**
     * @param string $userName
     * @param string $sortByField
     * @return array
     * @throws \Exception
     */
    public function getUserReposData(string $userName, string $sortByField = '')
    {
        if (!$this->session->has(self::USER_REPOS)) {
            $result = $this->getUserReposList($userName);
            $this->session->set(self::USER_REPOS, $result);
        }
        $reposData = $this->session->get(self::USER_REPOS);
        if (!$sortByField) {
            return $reposData;
        }

        return $this->sortReposData($reposData, $sortByField);
    }

    /**
     * @param array $reposData
     * @param string $sortByField
     * @return array
     */
    protected function sortReposData(array $reposData, string $sortByField)
    {
        \usort($reposData, $this->getComparator($sortByField));

        return $reposData;
    }

    /**
     * @param string $sortByField
     * @return \Closure
     */
    protected function getComparator(string $sortByField)
    {
        $number = function ($a, $b) use ($sortByField) {
            return $a[$sortByField] - $b[$sortByField];
        };
        $string = function ($a, $b) use ($sortByField) {
            return \strcmp($a[$sortByField], $b[$sortByField]);
        };
        $date = function ($a, $b) use ($sortByField) {
            $date1 = new \DateTime($a[$sortByField]);
            $date2 = new \DateTime($b[$sortByField]);

            return $date1 > $date2 ? 1 : -1;
        };

        $comparatorMap = [
            'name' => $string,
            'updated_at' => $date,
            'html_url' => $string,
            'commits_count' => $number,
        ];

        if (!isset($comparatorMap[$sortByField])) {
            throw new \InvalidArgumentException(\sprintf('Cannot sort by field: %s', $sortByField));
        }

        return $comparatorMap[$sortByField];
    }

    /**
     * @param string $userName
     * @return array
     * @throws \Exception
     */
    public function getUserReposList(string $userName)
    {
        $response = $this->client->getUserRepos($userName);
        $result = [];
        foreach ($response->getData() as $item) {
            $result[] = [
                'name' => $item['name'],
                'updated_at' => $this->formatDate($item['updated_at']),
                'html_url' => $item['html_url'],
                'commits_count' => $this->getRepoCommitsCount($userName, $item['name']),
            ];
        }

        return $result;
    }

    /**
     * @param string $userName
     * @param string $repoName
     * @return int
     * @throws \Exception
     */
    public function getRepoCommitsCount(string $userName, string $repoName)
    {
        $response = $this->client->getRepoContributors($userName, $repoName);

        if (!$response->isSuccessful()) {
            return 0;
        }

        $count = 0;
        foreach ($response->getData() as $contributor) {
            $count += $contributor['contributions'];
        }

        return $count;
    }

    /**
     * @param string $userName
     * @return mixed
     * @throws \Exception
     */
    public function getUserProfileLink(string $userName)
    {
        if (!$this->session->has(self::USER_PROFILE_LINK)) {
            $profileLink = $this->client->getProfileLink($userName) ?? '';
            $this->session->set(self::USER_PROFILE_LINK, $profileLink);
        }

        return $this->session->get(self::USER_PROFILE_LINK);
    }

    /**
     * @param string $date
     * @return string
     */
    protected function formatDate(string $date)
    {
        $dateTime = new \DateTime($date);

        return $dateTime->format(self::DATE_FORMAT);
    }
}
