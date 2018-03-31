<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 3/30/18
 * Time: 6:29 PM
 */

namespace App\Template;

class Template
{
    private $tpl;

    public function __construct($tpl)
    {
        $this->tpl = file_get_contents($tpl);
    }

    /**
     * @param array $placeHolders
     */
    public function render(array $placeHolders): void
    {
        foreach ($placeHolders as $placeHolder) {
            preg_replace(
                '/{{2}.*}{2}/',
                $placeHolders[$placeHolder],
                $this->tpl
            );
        }

        echo $this->tpl;
    }
}