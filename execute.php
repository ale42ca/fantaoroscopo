<?php
<?php
$web="https://api.telegram.org/bot";
$token="854019701:AAEharXPUNHUJm2vUD2mU2q4tY4DYHsqWXQ";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);



if(!$update){
  exit;
}
$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$utente=$messaggio['chat']['id'];
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$nomeutente=$messaggio['chat']['first_name'];
  $query = $update['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];

switch ($testo) {
    case "/start":
	$ms = "Ciao pronto per l'oroscopo?";
	sendMessage($utente, $ms);	
        tastierastart($utente);	
        break;
    case "classifica":
        $ms = "classifica";
	      sendMessage($utente, $ms);
	
        break;
    case "esci":	
	tastierastart($utente);	
   	break;	
}
if($testo == "vergine"){
		$ms = "creiamo evento";
		sendMessage($utente, $ms);
		$ms = "certamente";
		sendMessage($utente, $ms);
		exit();
}elseif($testo == "capricorno"){
		$ms = "quando vuole fare l' assemblea";
		sendMessage($admin, $ms);
		$msgcanale="prossima assemblea";		
		inviamessaggiocanale($msgcanale);	
}elseif($testo == "manda notifica"){
		$ms = "notifica inviata nel canale";
		$msgcanale="allert";
		sendMessage($admin, $ms);
		inviamessaggiocanale($msgcanale);
	
}
if($querydata == "ModificaMessaggio"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}
	
function tastierastart($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["aggiungi"],["togli"],[" "],[" "]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
function tastieracalendario($utente,$dataoggi){
    $message = $dataoggi;
   	$tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}]]}';
    $tastiera2 = '&reply_markup={"inline_keyboard":[[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}]]}';
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    $url2 = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML'.$message.$tastiera2;	
    file_get_contents($url);
}
function sendMessage($utente, $msg){
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
}
function editMessageText($chatId,$message_id,$newText){
    $url = $GLOBALS[completo]."/editMessageText?chat_id=$chatId&message_id=$message_id&parse_mode=HTML&text=".urlencode($newText);
    file_get_contents($url);
}

//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}
function deleteMessage($utente, $message_id){
	$url = $GLOBALS[completo]."/deleteMessage?chat_id=".$utente."&$message_id=".urlencode($message_id);
	file_get_contents($url);
}
function inserireneldatabase($utente,$dataoggi){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$query = "INSERT INTO prenotazioni (nome, quando, ora) VALUES ('$utente', '099','$dataoggi')";
	$result = pg_query($query);
}
function prendidaldatabase($utente){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$result = pg_query($db,"SELECT nome, quando, ora FROM prenotazioni  "); //WHERE quando = '099'
	
	while($row=pg_fetch_assoc($result)){
		$msg=$row['nome'].$row['quando'].$row['ora'] ;
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);	
	
	}
}

function deleterow(){
		$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
		$query = "DELETE FROM prenotazioni ";
		$result = pg_query($query);
	
}	






?>
