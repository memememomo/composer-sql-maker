<?php

use uchiko\SQL\Maker;

class NewLineTest extends PHPUnit_Framework_TestCase {

    // empty string
    public function testEmptyString() {
        $builder = new Maker(array('new_line' => '', 'driver' => 'mysql'));
        $this->assertEquals($builder->new_line, '');
    }

}
