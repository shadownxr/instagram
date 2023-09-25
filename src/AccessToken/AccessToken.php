<?php

namespace ArkonInstagram\AccessToken;

use Module;

class AccessToken {
    private $dependency;

    public function __construct(Module $dependency){
        $this->dependency = $dependency;
    }
}