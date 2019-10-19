<?php


namespace Command;


class cookControl
{
    private $mealCommand;
    private $drinkCommand;

    public function addCommand(Commands $mealCommand, Commands $drinkCommand){
        $this->mealCommand = $mealCommand;
        $this->drinkCommand = $drinkCommand;
    }

    public function callmeal(){
        $this->mealCommand->execute();
    }

    public function calldrink(){
        $this->drinkCommand->execute();
    }

}


$control = new cookControl();
$cook = new Command\Cook();

$mealCommand = new MealCommands($cook);
$drinkCommand = new DrinkCommand($cook);

$control->addCommand($mealCommand,$drinkCommand);
$control->callmeal();
$control->calldrink();