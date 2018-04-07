<?php

namespace App\Services\API;


abstract class AbstractGit implements GitInterface
{
    /**
     * @param $username
     * @return array
     */
    public function getRepositoriesWithCommitCount($username): array
    {
        $repositories = $this->getRepositories($username);

        foreach ($repositories as $key => $repository) {
            $commits = $this->getRepositoryCommits($username, $repository['name']);

            $repositories[$key]['commitCount'] = count($commits);
        }

        return $repositories;
    }
}