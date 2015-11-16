<?php
include("conn.php");
if(@$_GET['type']=='config'){
    $sql="select * from config ";
    $data=mysql_query($sql);
    while($row=mysql_fetch_array($data)){
        $p="<{$row['key']}>{$row['value']}</{$row['key']}>\n";
        echo $p;
    }
    
}
if(@$_GET['type']=='sqlmap'){
	$key=$_POST['key'];
	$url=$_POST['url'];
	$request=$_POST['request'];
    $userhash=$_POST['userhash'];
    $apiserver=$_POST['apiserver'];
	$key=addslashes($key);
	$url=addslashes(base64_decode(urldecode($url)));
	$request=addslashes(base64_decode(urldecode($request)));
	//$request=str_replace("\n",'<\n>',$request);
	$stat=file_get_contents("{$apiserver}/scan/{$key}/status");
	$stat=json_decode($stat);
	
	$sql="insert into sqlmap(`key`,request,url,status,userhash,apiserver)
	values('{$key}','{$request}','{$url}','{$stat->status}','{$userhash}','{$apiserver}')
	";
	echo $sql;
	mysql_query($sql);
    exit();
}
if($_GET['type']=='sqlmap_update'){
    header("Location: /index.php");
	$data=mysql_query("select * from sqlmap where status not in ('terminated','Inject')");
	while($row=mysql_fetch_array($data)){
		$key=$row['key'];
		$stat=file_get_contents("{$row['apiserver']}/scan/{$key}/status");
		$stat=json_decode($stat);
		$stat=$stat->status;
		$log=file_get_contents("{$row['apiserver']}/scan/{$key}/log");
		$log=json_decode($log);
		$log_array=array();
		foreach($log as $l){
			foreach($l as $ll){
				$log_array[]=$ll->message."\n";
				
			}
			
		}
		$dbtype=explode("the back-end DBMS is ",$log_array[count($log_array)-1]);
		if(isset($dbtype[1])){
			$dbtype=$dbtype[1];
			
		}
		else{
			$dbtype='UnKnow';
			// update dbtype
		}
		
		$log_array=var_export($log_array,true);
		$log_array=addslashes($log_array);
		
		

		
		mysql_query("update sqlmap set data='{$log_array}',status='{$stat}',dbtype='{$dbtype}' where `key`='{$key}'");

	}
    
    exit();
}
if($_GET['type']=='sqlmap_clear'){
    header("Location: /index.php");
    mysql_query("delete from sqlmap where status not in ('running','Inject')  and userhash='{$hash}'");
    exit();
}if($_GET['type']=='sqlmap_clearall'){
    header("Location: /index.php");
    mysql_query("delete from sqlmap  where status not in ('Inject')  and userhash='{$hash}';");
    exit();
}if($_GET['type']=='sqlmap_clearvuls'){
    header("Location: /index.php");
    mysql_query("delete from sqlmap  where status  in ('Inject')  and userhash='{$hash}';");
	mysql_query("delete from dirscan ;");
    exit();
}
if($_GET['type']=='hash'){
    $key=addslashes($_GET['key']);
    $hash=addslashes($_GET['hash']);
    $sql="update sqlmap set `hash`='{$hash}' where `key`='{$key}'";
    echo $sql;
    mysql_query($sql);
    
    
    
    
    
    
    
}
if($_GET['type']=='hash_test'){
    $hash=addslashes($_GET['hash']);
    $sql=mysql_query("select `key` from sqlmap where hash='{$hash}'",$conn);
    $data=mysql_fetch_array($sql);
    //var_dump($data);
    if($data['key']!=''){
        echo 'is false';
        exit();
        
    }
    echo 'is true';
    exit();
    
    
    
    
    
    
}if($_GET['type']=='dirscan_add'){
	$code=intval($_GET['code']);
	$url=addslashes(base64_decode($_GET['url']));
	$sql="insert into dirscan(url,code) values('{$url}','{$code}')";
	mysql_query($sql);
    exit();
}if($_GET['type']=='dirscan'){
	ignore_user_abort();
	set_time_limit(0);
	$urls=mysql_query("select url from sqlmap where status='inject' and dirscan=0");
	$host=array();
	$write='';
	while($row=mysql_fetch_array($urls)){
		$h=parse_url($row['url']);
		$host[]=$h['host'];
		$isscan=mysql_query("select count(*) as count from dirscan where url like '%{$h['host']}%'");
		mysql_query("update sqlmap set dirscan=1 where url='{$row['url']}'");
		$write.=$row['url']."\n";
		/*
		while($rows=mysql_fetch_array($isscan)){
			
			if($rows['count']==0){
				$write.=$row['url']."\n";
			}
		}*/
				
	}
	print $write;
	file_put_contents("scanurls.txt",$write);
	system("python dirscan.py");
	echo "<script>
	window.opener.location.href='http://run.pwn.ren/index.php?vul=1';
	</script>";
    exit();
}if($_GET['type']=='getapi'){

$row=mysql_query("select DISTINCT ip as api,(select count(*) from sqlmap where apiserver=api and  status not in ('terminated','Inject') ) as count from apiconfig order by count limit 1");
$row=mysql_fetch_array($row);

die($row['api']);

//die('http://127.0.0.1:8775');


}