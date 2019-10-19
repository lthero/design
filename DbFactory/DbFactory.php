<?php


class DbFactory
{
    public static function factorys($type){
        if(include_once 'Derivers/'.$type.'.php'){
            $classname = 'Db_Adapter_'.$type;
            return new $classname;
        }else{
            throw new Exception('Driver not found');
        }
    }
}

//调用

$db = factory::factorys('Mysql');