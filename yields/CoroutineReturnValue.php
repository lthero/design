<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/10/29
 * Time: 15:58
 */



class CoroutineReturnValue {
    protected $value;
    public function __construct($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
}