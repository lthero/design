<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/5
 * Time: 11:05
 * 正常收费
 */
class CashNormal extends CashSuper {
    public function acceptCash(float $money)
    {
        return $money;
    }
}