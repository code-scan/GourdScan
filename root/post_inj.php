<form method='POST'>
<input type='text' name='c'>
<submit>
</form>
<?php

$conn=mysql_connect("127.0.0.1",'root','root');
mysql_select_db("pscan",$conn);
$q=mysql_query("select * from mysql.user where 1={$_POST['c']}",$conn);
if($_COOKIE['a']){
	$q=mysql_query("select * from mysql.user where 1={$_POST['c']}",$conn);
	while($row=mysql_fetch_array($q)){
	foreach($row as $qq){
		echo $qq."<br>";
	}
}
}

?>