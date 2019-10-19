<?php


namespace Command;


class DrinkCommand implements Commands
{
    private $cook;

    public function __construct(Cook $cook)
    {
        $this->cook = $cook;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $this->cook->drink();
    }
}