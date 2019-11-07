<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/7
 * Time: 13:43
 */
abstract class Decorator extends Component{
    protected $component;

    public function SetComponent(Component $component){
        $this->component = $component;
    }

    public function Operation(){
        if(!empty($this->component)){
            $this->component->Operation();
        }
    }
}