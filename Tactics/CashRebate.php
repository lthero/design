<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/5
 * Time: 11:52
 * 折扣
 */
class CashRebate extends CashSuper {
    private $moneyRebate = 1;

    public function CashRebate($moneyRebate){
        $this->moneyRebate = round($moneyRebate,2);

    }

    public function acceptCash(float $money)
    {
        return $money * $this->moneyRebate;
    }
}
