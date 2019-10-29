<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/10/25
 * Time: 14:28
 */
require "C:\Users\admin\Desktop\github\design\yields\Scheduler.php";

    //php 内置的生成器 和生成器函数 和spl内置数据结构。

// 系统调用 在利用一组socket的监听
 function waitForRead($socket) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($socket) {
                $scheduler->waitForRead($socket, $task);
            }
        );
    }
// 系统调用 在利用一组socket的写入 调用系统调用器将任务生成器和调度器传入进行socket 写入
  function waitForWrite($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForWrite($socket, $task);
        }
    );
}


function retval($value) {
        return new CoroutineReturnValue($value);
}

function stackedCoroutine(Generator $gen) {
        $stack = new SplStack;
        $exception = null;
        for (;;) {
            try {
                if ($exception) {
                    $gen->throw($exception);
                    $exception = null;
                    continue;
                }
                $value = $gen->current();
                if ($value instanceof Generator) {
                    $stack->push($gen);
                    $gen = $value;
                    continue;
                }
                $isReturnValue = $value instanceof CoroutineReturnValue;
                if (!$gen->valid() || $isReturnValue) {
                    if ($stack->isEmpty()) {
                        return;
                    }
                    $gen = $stack->pop();
                    $gen->send($isReturnValue ? $value->getValue() : NULL);
                    continue;
                }
                try {
                    $sendValue = (yield $gen->key() => $value);
                } catch (Exception $e) {
                    $gen->throw($e);
                    continue;
                }
                $gen->send($sendValue);
            } catch (Exception $e) {
                if ($stack->isEmpty()) {
                    throw $e;
                }
                $gen = $stack->pop();
                $exception = $e;
            }
        }
    }

function server($port) {
        echo "Starting server at port $port...\n";
        $socket = @stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
        if (!$socket) throw new Exception($errStr, $errNo);
        stream_set_blocking($socket, 0);
        $socket = new CoSocket($socket);
        while (true) {
            yield newTask(
                handleClient(yield $socket->accept())
            );
        }
    }

function handleClient($socket) {
        $data = (yield $socket->read(8192));
        $msg = "Received following request:\n\n$data";
        $msgLength = strlen($msg);
        $response = <<<RES
HTTP/1.1 200 OK\r
Content-Type: text/plain\r
Content-Length: $msgLength\r
Connection: close\r
\r
$msg
RES;
        yield $socket->write($response);
        yield $socket->close();
}

//获取 新的线程id
function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}


//创建新的线程
function newTask(Generator $coroutine) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

// 在线程中新开子线程
function childTask() {
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}

// kill 线程
function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            if ($scheduler->killTask($tid)) {
                $scheduler->schedule($task);
            } else {
                throw new InvalidArgumentException('Invalid task ID!');
            }
        }
    );
}

function task() {
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));
    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
        if ($i == 3) yield killTask($childTid);
    }
}

$scheduler = new Scheduler();
$scheduler->newTask(task());
$scheduler->run();

