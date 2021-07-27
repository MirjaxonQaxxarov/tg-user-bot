<?php
error_reporting(0);
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
if (!file_exists('Config.json')) {
  file_put_contents('Config.json', '{"Monshi":0,"Markread":0,"Typing":0,"Poker":0,"Enemy":0}');
}
if (!file_exists('users.db')){
    file_put_contents('users.db','{"userID":[]}');
}
define('MADELINE_BRANCH', 'deprecated');
include "madeline.php";
    function closeConnection($message = 'MCoder.Uz')
{
    if (php_sapi_name() === 'cli' || isset($GLOBALS['exited'])) {
        return;
    }
    @ob_end_clean();
    header('Connection: close');
    ignore_user_abort(true);
    ob_start();
    echo "$message";
    $size = ob_get_length();
    header("Content-Length: $size");
    header('Content-Type: text/html');
    ob_end_flush();
    flush();
    $GLOBALS['exited'] = true;
}
function shutdown_function($lock)
{
    $a = fsockopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'tls' : 'tcp').'://'.$_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT']);
    fwrite($a, $_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' '.$_SERVER['SERVER_PROTOCOL']."\r\n".'Host: '.$_SERVER['SERVER_NAME']."\r\n\r\n");
    flock($lock, LOCK_UN);
    fclose($lock);
}
if (!file_exists('bot.lock')) {
    touch('bot.lock');
}
$lock = fopen('bot.lock', 'r+');

$try = 1;
$locked = false;
while (!$locked) {
    $locked = flock($lock, LOCK_EX | LOCK_NB);
    if (!$locked) {
        closeConnection();

        if ($try++ >= 30) {
            exit;
        }
        sleep(1);
    }
}
$MadelineProto = new \danog\MadelineProto\API('JD.madeline');
$MadelineProto->start();
$offset = 0;

register_shutdown_function('shutdown_function', $lock);
closeConnection();

while (true) {
    $updates = $MadelineProto->get_updates(['offset' => $offset, 'limit' => 50, 'timeout' => 0]);
    foreach ($updates as $update) {
        $offset = $update['update_id'] + 1;
     $up = $update['update']['_'];
                if ($up == 'updateNewMessage' or $up == 'updateNewChannelMessage' or $up == 'updateEditChannelMessage') {
$chatID = $MadelineProto->get_info($update['update']);
$type = $chatID['type'];
$chatID = $chatID['bot_api_id'];
$userID = $update['update']['message']['from_id'];
$msg = $update['update']['message']['message'];
$msg_id = $update['update']['message']['id'];
$date=file_get_contents("send$userID.txt");
$list=json_decode(file_get_contents("users.db"));
$users=$list->userID;
if($msg){
    if(!(is_numeric(array_search($userID,$users)))){
        array_push($list->userID,$userID);
        file_put_contents('users.db', json_encode($list));
        $list=json_decode(file_get_contents("users.db"));
        $users=$list->userID;
    }
    
}

if($msg == "/send"){
 $Conf = json_decode(file_get_contents('Config.json'));
$Conf->Enemy = 1;
file_put_contents('Config.json', json_encode($Conf));
file_put_contents("send$userID.txt","sett");
$ed = $MadelineProto->messages->editMessage(['peer' => $chatID, 'id' => $msg_id, 'message' =>'Yubormoqchi bulgan xabaringizni yozing!  Bekor qilish uchun /cancelni yuboring!', 'parse_mode' => 'MarkDown' ]);
}

if($msg == "/cancel"&& $date=="sett"){
file_put_contents("send$userID.txt","");
$Conf = json_decode(file_get_contents('Config.json'));
$Conf->Enemy = 1;
$ed = $MadelineProto->messages->editMessage(['peer' => $chatID, 'id' => $msg_id, 'message' =>'Yuborish Bekor qilindi! ', 'parse_mode' => 'MarkDown' ]);
}
elseif($msg && $date=="sett"){
   foreach ($users as $key => $value) {
        $date=file_get_contents("send$userID.txt");
        $Conf = json_decode(file_get_contents('Config.json'));
        $Conf->Enemy = 1;
        $ed = $MadelineProto->messages->sendMessage(['peer' => $value, 'message' =>$msg, 'parse_mode' => 'MarkDown' ]);
        if($date=="sett")
        $date=file_put_contents("send$userID.txt","");
   }
}


if($msg == "/memb"){
 $Conf = json_decode(file_get_contents('Config.json'));
$Conf->Enemy = 1;
$txs=count($users);
$ed = $MadelineProto->messages->editMessage(['peer' => $chatID, 'id' => $msg_id, 'message' =>$txs, 'parse_mode' => 'MarkDown' ]);
}


     
                try {

    include 'function.php';
}catch(Exception $e){
    }
    catch(\danog\MadelineProto\RPCErrorException $e){
        $MadelineProto->messages->sendMessage(['peer' => 1246669537, 'message' => $e]);
    }
    catch(\danog\MadelineProto\Exception $e){
    }
    catch(\danog\MadelineProto\TL\Conversion\Exception $e){
    }
}
}
}
?>