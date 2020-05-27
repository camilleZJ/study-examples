<?php
/**
 * 动态计划处理
 * @copyright	Copyright (c) 2004 - 2018, Qinhe Co.,Ltd. (http://www.ispeak.cn/)
 * @since	Date 2018年3月19日
 * @author	iSpeak Dev Team <ZhaoJing>
 */
header("content-type:text/html;charset=utf-8");
class DynamicTask
{
	
	
	public function processDynamicTask()
	{
// 		/**
// 		 * 获取待处理的任务
// 		 * 如果没有符合条件的id结束操作。
// 		 */
// 		if(! $this->_getTasksByStatus(self::STATUS_CREATED,$limit) || (! $this->_taskCollections))
// 		{
// 			return false;
// 		}
	
// 		foreach($this->_taskCollections as $collection)
// 		{
// 			if($this->_processTaskById($collection))
// 			{
// 				$this->_setTaskStatus($collection->id,self::STATUS_FINISHED);
// 				Log::info(__CLASS__.':'.__FUNCTION__.': Dynamic backend task '.$collection->id . ' has been finished');
// 			}
// 		}
// 		return true;

// echo '$limit = self::TASKS_LIMIT';
	}

	
}

// $task = new DynamicTask();
// $task->processDynamicTask();

$stack = new SplStack();
$stack->push('data1<br>');
$stack->push('data2<br>');

echo $stack->pop();
echo $stack->pop();


$queue = new SplQueue();
$queue->enqueue('data3<br>');
$queue->enqueue('data4<br>');

echo $queue->dequeue();
echo $queue->dequeue();

$heap = new S