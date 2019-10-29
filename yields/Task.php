<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/10/29
 * Time: 15:56
 */

class Task {
    // 线程ID
    protected $taskId;
    // 调度器实例
    protected $coroutine;
    //发送的值
    protected $sendValue = null;
    //
    protected $beforeFirstYield = true;
    // 异常对象
    protected $exception = null;

    // 异常处理设置
    public function setException($exception) {
        $this->exception = $exception;
    }

    // 设置线程ID号 ， 设置调度器  SPL生成器函数
    public function __construct($taskId, Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    // 获取线程ID
    public function getTaskId() {
        return $this->taskId;
    }
    //设置发送的 值
    public function setSendValue($sendValue) {
        $this->sendValue = $sendValue;
    }

    // 运行函数
    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            // 生成器继续执行
            return $this->coroutine->current();
        } elseif ($this->exception) {
            // 错误存在就抛出异常
            $retval = $this->coroutine->throw($this->exception);
            $this->exception = null;
            return $retval;
        } else {
            // 给生成器一个值
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
    // 检查是否被关闭
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}