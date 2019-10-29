<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/10/29
 * Time: 15:57
 */



//系统调用
class SystemCall {
    protected $callback;
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    public function __invoke(Task $task, Scheduler $scheduler) {
        $callback = $this->callback; // Can't call it directly in PHP :/
        return $callback($task, $scheduler);
    }

}