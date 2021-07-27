<?php

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
if (!file_exists('users.db')) {
    file_put_contents('users.db','{"userID":[]}');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('JD.madeline');
$MadelineProto->async(true);

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
