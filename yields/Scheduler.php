<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/10/29
 * Time: 15:57
 */

require "C:\Users\admin\Desktop\github\design\yields\Task.php";
require "C:\Users\admin\Desktop\github\design\yields\SystemCall.php";
//调度器
class Scheduler {

    // 最大生成器限制
    protected $maxTaskId = 0;
    // 线程数组
    protected $taskMap = []; // taskId => task
    // 线程队列
    protected $taskQueue;

    //读取缓冲
    protected $waitingForRead = [];
    // 写入缓冲
    protected $waitingForWrite = [];

    // spl的链表
    public function __construct() {
        $this->taskQueue = new SplQueue();
    }
    //开启一个新的线程
    public function newTask(Generator $coroutine) {
        $tid = ++$this->maxTaskId;
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }
    // 放入队列
    public function schedule(Task $task) {
        $this->taskQueue->enqueue($task);
    }

    //
    public function run() {
        while (!$this->taskQueue->isEmpty()) {
            $task = $this->taskQueue->dequeue();
            $retval = $task->run();
            if ($retval instanceof SystemCall) {
                try {
                    $retval($task, $this);
                } catch (Exception $e) {
                    $task->setException($e);
                    $this->schedule($task);
                }
                continue;
            }
            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->schedule($task);
            }
        }
    }
    // 干掉线程
    public function killTask($tid) {
        if (!isset($this->taskMap[$tid])) {
            return false;
        }
        unset($this->taskMap[$tid]);
        // This is a bit ugly and could be optimized so it does not have to walk the queue,
        // but assuming that killing tasks is rather rare I won't bother with it now
        foreach ($this->taskQueue as $i => $task) {
            if ($task->getTaskId() === $tid) {
                unset($this->taskQueue[$i]);
                break;
            }
        }
        return true;
    }
    //读取socket中的任务

    public function waitForRead($socket, Task $task) {
        if (isset($this->waitingForRead[(int) $socket])) {
            $this->waitingForRead[(int) $socket][1][] = $task;
        } else {
            $this->waitingForRead[(int) $socket] = [$socket, [$task]];
        }
    }

    // 写入
    public function waitForWrite($socket, Task $task) {
        if (isset($this->waitingForWrite[(int) $socket])) {
            $this->waitingForWrite[(int) $socket][1][] = $task;
        } else {
            $this->waitingForWrite[(int) $socket] = [$socket, [$task]];
        }
    }

    //
    protected function ioPoll($timeout) {
        $rSocks = [];
        foreach ($this->waitingForRead as list($socket)) {
            $rSocks[] = $socket;
        }
        $wSocks = [];
        foreach ($this->waitingForWrite as list($socket)) {
            $wSocks[] = $socket;
        }
        $eSocks = []; // dummy
        if (!stream_select($rSocks, $wSocks, $eSocks, $timeout)) {
            return;
        }
        foreach ($rSocks as $socket) {
            list(, $tasks) = $this->waitingForRead[(int) $socket];
            unset($this->waitingForRead[(int) $socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
        foreach ($wSocks as $socket) {
            list(, $tasks) = $this->waitingForWrite[(int) $socket];
            unset($this->waitingForWrite[(int) $socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
    }

    protected function ioPollTask() {
        while (true) {
            if ($this->taskQueue->isEmpty()) {
                $this->ioPoll(null);
            } else {
                $this->ioPoll(0);
            }
            yield;
        }
    }
}