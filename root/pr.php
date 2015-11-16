<?php
echo "callback(0);";die();
include("conn.php");
function app_hash_url($url){
	$seed="Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE.";
	$result=0x01020345;
	for($i=0;$i<strlen($url);$i++){
		$result^=ord($seed{$i%87})^ord($url{$i});
		$result=(($result>>23)&0x1FF)|$result<<9;
	}
	return sprintf("8%x",$result);
}


function get_pr($url){
	$PR_CH=app_hash_url($url);
    $url='http://toolbarqueries.google.com/tbr?client=navclient-auto&features=Rank&q=info:'.$url.'&ch='.$PR_CH;
	$prtext=file_get_contents($url);
	if($prtext!=""){
	$pr=explode(":",$prtext);
	return $pr[2];
	}else{
	return 0;
	}
}
$query=mysql_query("select pr from sqlmap where `key`='{$_GET['key']}' or url like '%{$_GET['host']}%'");
$pr=mysql_fetch_array($query);
if($pr[0]!=='-2'){
	echo "callback({$pr[0]});";
	exit();
}

$pr=get_pr($_GET['host']);
$pr=str_replace("\n",'',$pr);
$pr=str_replace("\r",'',$pr);
if($pr==''){
	$pr='-1';
}
echo "callback({$pr});";

mysql_query("update sqlmap set pr='{$pr}' where `key`='{$_GET['key']}'");
#echo "update sqlmap set pr='{$pr}' where `key`='{$_GET['key']}'";
?>
