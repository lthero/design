<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/8
 * Time: 16:15
 */
class AddFactory implements IFactory{

    public function CreateOperation()
    {
        // TODO: Implement CreateOperation() method.
        return new OperationAdd();
    }
}

class SubFactory implements IFactory{

    public function CreateOperation()
    {
        // TODO: Implement CreateOperation() method.
        return new OperationSub();
    }
}