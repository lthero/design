<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/7
 * Time: 11:08
 * 返利子类
 */

class CashReturn extends CashSuper
{
    private $moneyCondition = 0;

    private $moneyReturn = 0;

    public function CashReturn($moneyReturn,$moneyCondition){
        $this->moneyCondition = $moneyCondition;
        $this->moneyReturn = $moneyReturn;
    }

    public function acceptCash(float $money)
    {
        $result = $money;
        if($money >=$this->moneyCondition){
            $result = $money-($money/$this->moneyCondition) * $this->moneyReturn;
        }
        return $result;
    }


}