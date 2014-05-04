<?php

namespace uchiko\SQL\Maker;

class Scalar {
    public $data;

    public function __construct($d) {
        $this->data = $d;
    }

    public function raw() {
        return $this->data;
    }
}
