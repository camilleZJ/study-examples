<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$time = date('r'); //小写r表示RFC822日期
echo "data: The server time is: {$time}\n\n";
flush();
?>
