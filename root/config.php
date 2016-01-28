<?php
include("conn.php");
if(@$_GET['hash']){
$_SESSION['hash']=$_GET['hash'];
}
$hash=@$_SESSION['hash'];

if(@$_GET['action']=='stopproxy'){
    /*
    $proxy=shell_exec('netstat -ano|find "8080"');
    $proxy=explode('LISTENING',$proxy);
    $port=$proxy[2];
    $port=str_replace(" ",'',$port);
    $port=str_replace("\t",'',$port);
    $port=str_replace("\n",'',$port);
    shell_exec('taskkill /f /pid {$port}');
    */
   // die();
}
if(@$_GET['action']=='startproxy'){

    shell_exec('../run_proxy.bat');
   // die();
}

if(@$_POST['action']=='delete'){
    if($_POST['table']=='apiconfig'){
        $sql="delete from apiconfig where id=".intval($_POST['id']);
        mysql_query($sql);
    }
    
}
if(@$_POST['action']=='edit'){
    if($_POST['table']=='config'){
        $key=addslashes($_POST['key']);
        $value=addslashes($_POST['value']);
        $id=intval($_POST['id']);
        $sql="update config set `key`='{$key}',`value`='{$value}' where id='{$id}'";
        mysql_query($sql);
    }
    if($_POST['table']=='apiconfig'){
        $key=addslashes($_POST['ip']);
        $value=addslashes($_POST['contents']);
        $id=intval($_POST['id']);
        $sql="update apiconfig set `ip`='{$key}',`contents`='{$value}' where id='{$id}'";
        //die($sql);
        mysql_query($sql);
    }
}
if(@$_POST['action']=='add'){
    if($_POST['table']=='config'){
        $key=addslashes($_POST['key']);
        $value=addslashes($_POST['value']);        
        $sql="insert into config(`key`,`value`) values('{$key}','{$value}')";
        mysql_query($sql);
    }
    if($_POST['table']=='apiconfig'){
        $key=addslashes($_POST['ip']);
        $value=addslashes($_POST['contents']);        
        $sql="insert into apiconfig(`ip`,`contents`) values('{$key}','{$value}')";
        mysql_query($sql);
    
    }

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>GourdScan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">
	<script src="js/jquery.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <?php 
					
				
				
				$query=mysql_query("select count(*) as row from sqlmap where hash!='' and userhash='{$hash}'");
				$count='';
				while($row=mysql_fetch_array($query)){
					$count=$row['row'];
					
				} 
				$query=mysql_query("select count(*) as row from sqlmap where hash!='' and status='running' and userhash='{$hash}'");
				$rcount='';
				while($row=mysql_fetch_array($query)){
					$rcount=$row['row'];
					
				} 				
				$query=mysql_query("select count(*) as row from sqlmap where hash!='' and status='terminated' and userhash='{$hash}'");
				$tcount='';
				while($row=mysql_fetch_array($query)){
					$tcount=$row['row'];
					
				} 
				$query=mysql_query("select count(*) as row from sqlmap where hash!='' and status='Inject' and userhash='{$hash}'");
				$icount='';
				while($row=mysql_fetch_array($query)){
					$icount=$row['row'];
					
				}			
					 ?>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						 <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
					</button> 			<ul class="nav navbar-nav navbar-right">
						<li class="">
							<a href="./"></a>
					</li>
						<li class="dropdown">
							 <a href="#" class="navbar-brand" data-toggle="dropdown">GourdScan<strong class="caret"></strong></a>
							<ul class="dropdown-menu">

								<li>
									<a class="navbar-brand" href="#">Count:<?php echo $count?></a>
								</li>
							
								<li>
									<a class="navbar-brand" href="#">Running:<?php echo $rcount?></a>
								</li>
								
								<li>
									<a class="navbar-brand" href="#">Terminated:<?php echo $tcount?></a>
								</li>
								
								<li>
									<a class="navbar-brand" href="#">Inject:<?php echo $icount?></a>
								</li>

							</ul>
						</li>
					</ul>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
					<li class="">
							<a href="./"></a>
					</li>
					<?php 
					$index='active';
					$v='';
					if(@$_GET['vul']){
						$v='active';
						$index='';
					}
					?>
					
						<li class="">
							<a href="./">Index</a>
						</li>
                        
                        <li class="<?php echo $index?>">
							<a href="config.php">Config</a>
						</li>
                        
						<li class="<?php echo $v?>">
							<a href="index.php?vul=1">Vuls</a>
						</li>
				
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Action<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									
								</li>
								<?php if(@$_GET['vul']){?>
								<li>
									<a href="api.php?type=dirscan" target="_black">Dirscan</a>
								</li>
								<?php }?>
							
								<li>
									<a href="api.php?type=sqlmap_clear">Clear</a>
								</li>
								<li>
									<a href="api.php?type=sqlmap_clearall">Clear All</a>
								</li>
								<li>
									<a href="api.php?type=sqlmap_clearvuls">Clear Vuls</a>
								</li>
								
					
							</ul>
						</li>
					</ul>
					<form action="" method="POST" class="navbar-form navbar-left" role="search">
						<div class="form-group">
							<input type="text" name="search" class="form-control">
						</div> 
						<button type="submit" class="btn btn-default">
							Submit
						</button>
					</form>
		
				</div>
				
			</nav>
	
                
             <a class="navbar-brand" href="#">Action</a>
			<table class="table">
				<thead>
					<tr>
						<th>
					
                            Button(暂不可用)
						</th>
                     
					</tr>
				</thead>
				<tbody>
			
	
           <form action="" method='POST'>
					<tr class='warning'>
						<td>
						
                   <center>
                         <a href="config.php#?action=startproxy" type='submit' disable="true" class="btn btn-default" value="Add....." >开启代理</a>
                         <a href="config.php#?action=stopproxy" type='submit'  class="btn btn-default" value="Add....." >关闭代理</a>
                         <a href="config.php#?action=startupdate" type='submit'  class="btn btn-default" value="Add....." >关闭自动更新</a>
                         <a href="config.php#?action=stopupdate" type='submit'  class="btn btn-default" value="Add....." >开启自动更新</a>
                         </center>
                        </td>
                

                        
			</form>
				</tbody>
			</table>
            
      <a class="navbar-brand" href="#">List</a>
			<table class="table">
				<thead>
					<tr>
						<th>
					
                            ID
						</th>
                        <th>
					
                            UserHash
						</th>
                          <th>
					
                            All Count
						</th>
                            <th>
                            Running
                            
						</th>
                            <th>
					
                            Inject
						</th>
                            <th>
                            Not Running
                            
						</th>
                            <th>
					terminated
                            
						</th>
					</tr>
				</thead>
				<tbody>
			
	<?php 
    $userhash=mysql_query("select DISTINCT userhash from sqlmap");
    while($user=mysql_fetch_array($userhash)){

        $rows=mysql_query("select (select count(*) from sqlmap where userhash='{$user['userhash']}') as allcount,
                                  (select count(*) from sqlmap where userhash='{$user['userhash']}' and status='running') as running,
                                  (select count(*) from sqlmap where userhash='{$user['userhash']}' and status='not running') as notrunning,
                                  (select count(*) from sqlmap where userhash='{$user['userhash']}' and status='Inject') as inject,
                                  (select count(*) from sqlmap where userhash='{$user['userhash']}' and status='terminated') as `terminated`");
        $row=mysql_fetch_array($rows);?>
   
         
					<tr class='warning'>
						<td>
                            1
                        </td>
                        	<td>
                            <a href="index.php?hash=<?php echo $user['userhash'];?> ">
                        <?php echo $user['userhash'];?>   </a>
                        </td>
                        <td>
                          <?php echo $row['allcount']?>
                        </td>
                <td><?php echo $row['running']?></td>
                <td><?php echo $row['inject']?></td>
                <td><?php echo $row['notrunning']?></td>
                <td><?php echo $row['terminated']?></td>

                    <?php  }
                    ?>    
			
				</tbody>
			</table>
            
            
    
			<a class="navbar-brand" href="#">SQLmap Server</a>
			<table class="table">
				<thead>
					<tr>
						<th>
							ID
						</th>
						<th>
							Address
						</th>
						<th>
                            Contents
						</th>
                        
                     
					</tr>
				</thead>
				<tbody>
			
            
            
            
            
            

			<?php
            $apiserver=mysql_query("select * from apiconfig");
            while($row=mysql_fetch_array($apiserver)){
            ?>
                    <form action="" method='POST'>
					<tr class='warning'>
						<td>
							<?php echo $row['id'];?>              
						</td>
						<td>
                         <textarea class="form-control"  name='ip'  rows='1' cols='85'><?php echo $row['ip'];?></textarea> 
						
						</td>
						<td>
						<textarea class="form-control" name='contents' rows='1'  cols='85'><?php echo $row['contents'];?></textarea> 
							
							
						</td>
                        <td>
                        
                        <input type='hidden' name="id" value="<?php echo $row['id'];?>">
                        <input type='hidden' name="action" value="edit">
                        <input type='hidden' name="table" value="apiconfig">
                        <input type='submit'  class="btn btn-default" value="Submit" >
                         </form>
                         </td>
                         <td>
                          <form action="" method="post">
                        <input type='hidden' name="id" value="<?php echo $row['id'];?>">
                        <input type='hidden' name="action" value="delete">
                        <input type='hidden' name="table" value="apiconfig">
                        <input type='submit'  class="btn btn-default" value="Delete" >
                        </form>
                         
                        </td>
                       
                    
                       
                       <?php  
                        }
                        ?>
                        <form action="" method='POST'>
                        <tr class='warning'>
						<td>
							Add           
						</td>
						<td>
                         <textarea class="form-control"  name='ip'  rows='1' cols='85'></textarea> 
						
						</td>
						<td>
						<textarea class="form-control" name='contents' rows='1' cols='85'></textarea> 
							
							
						</td>
                        <td>
                        <input type='hidden' name="action" value="add">
                        <input type='hidden' name="table" value="apiconfig">
                        <input type='submit'  class="btn btn-default" value="Add....." >
                        </td>
                        </form>
                        <td></td>
               
			
				</tbody>
			</table>
            
            <a class="navbar-brand" href="#">Scan Config</a>
			<table class="table">
				<thead>
					<tr>
						<th>
							ID
						</th>
						<th>
							Key
						</th>
						<th>
                            Value
						</th>
                     
					</tr>
				</thead>
				<tbody>
			
			<?php
            $apiserver=mysql_query("select * from config");
            while($row=mysql_fetch_array($apiserver)){
            ?>
           <form action="" method='POST'>
					<tr class='warning'>
						<td>
							<?php echo $row['id'];?>
                            
						</td>
						<td>
						
                        <textarea class="form-control" name="key" rows='1' cols='85'><?php echo $row['key'];?></textarea> 
						</td>
						<td>
						 <textarea class="form-control" name="value" rows='1' cols='85'><?php echo $row['value'];?></textarea> 
							
							
						</td>
                        
                        <td>
                        <input type='hidden' name="action" value="edit">
                        <input type='hidden' name="table" value="config">
                        <input type='hidden' name="id" value="<?php echo $row['id'];?>">
                        <input type='submit' class="btn btn-default" value="Submit" >
                        
                        </td></form>
					
                       <?php  
                        }
                        ?>
                        
                        <form action="" method='POST'>
                        <tr class='warning'>
						<td>
							Add           
						</td>
						<td>
                         <textarea class="form-control"  name='key'  rows='1' cols='85'></textarea> 
						
						</td>
						<td>
						<textarea class="form-control" name='value' rows='1' cols='85'></textarea> 
							
							
						</td>
                        <td>
                        <input type='hidden' name="action" value="add">
                        <input type='hidden' name="table" value="config">
                        <input type='submit'  class="btn btn-default" value="Add....." >
                        </td>
                        </form>
                        
			</form>
				</tbody>
			</table>

            
            
		</div>
	</div>
</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
