<?php
/**
 * Copyright (c) 2018.
 *
 * @author        Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp\Model;

/**
 * Interface UserRepository
 * @package WebApp\Model
 */
interface UserRepository
{

    /**
     * UserRepository constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db);

    /**
     * @param string $name
     * @param string $password
     */
    public function getUserByNameAndPassword(string $name, string $password);
}
