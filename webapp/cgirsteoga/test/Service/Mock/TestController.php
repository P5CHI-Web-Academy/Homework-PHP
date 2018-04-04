<?php

namespace Webapp\Test\Service\Mock;

class TestController
{
    const TEST_CONTENT = 'test passed';

    /**
     * @return bool
     */
    public function testAction()
    {
        return self::TEST_CONTENT;
    }
}
