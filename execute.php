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
		$ms = "punti";
		sendMessage($utente, $ms);
		prendidaldatabase($utente){
		aggiungi punti();	
		sendMessage($utente, $ms);
		exit();
}elseif($testo == "capricorno"){
		$ms = "punti";
		sendMessage($utente, $ms);
		prendidaldatabase($utente)
		aggiungi punti();
		sendMessage($utente, $ms);
		exit();	
}elseif($testo == "classifica"){
		$ms = "la classifica";
		sendMessage($utente, $ms);
		prendidaldatabase($utente){
		sendMessage($utente, $ms);
		exit();	
}
if($querydata == "ModificaMessaggio"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}
	
function tastierastart($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["vergine"],["capricorno"],["classifica"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
			
function aggiungipuntitastiera($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["++"],["capricorno"],["--"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
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
