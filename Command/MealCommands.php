<?php

namespace Command;

class MealCommands implements Commands
{
    //绑定命令接受者
    private $cook;

    public function __construct(Cook $cook)
    {
        $this->cook = $cook;
    }

    public function execute()
    {
        $this->cook->meal();
    }
}