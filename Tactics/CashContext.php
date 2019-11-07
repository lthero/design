<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/7
 * Time: 11:15
 */

class CashContext
{
     private $CashSuper;

     public function __construct(CashSuper $cashSuper)
     {
         $this->CashSuper = new $cashSuper;
     }

     public function GetResult($money)
     {
       return $this->CashSuper->acceptCash($money);
     }
}