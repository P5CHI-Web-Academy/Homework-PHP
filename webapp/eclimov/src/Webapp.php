<?php

namespace Webapp;

use Service\Templater;
use Service\TemplaterService;

class Webapp {
    /*
        I don't see purpose of this file, but looks like it may become core of the application in the future.
        Leave it for now.
    */

    private $templater;

    public function __construct(Templater $templater)
    {
        $this->templater = $templater;
    }

    public function sampleForTest(int $number){
        return $number*$number/2;
    }
}