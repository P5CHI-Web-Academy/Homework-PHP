<?php
/**
 * Copyright (c) 2018.
 *
 *  @author		Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp;


/**
 * Class Config
 * @package WebApp
 */
class Config
{
    /**
     * @var array
     */
    private $data;

    /**
     * Config constructor.
     */
    public function __construct() {
    	$this->data = require __DIR__ . '/../app/config.php';
    }

    /**
     * @param $key
     * @return null
     */
    public function __get( $key ) {
        return $this->data[$key] ?? NULL;
    }
}