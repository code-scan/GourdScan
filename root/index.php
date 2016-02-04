<?php
include("conn.php");

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
					
						<li class="<?php echo $index?>">
							<a href="./">Index</a>
						</li>
                        <li>
							<a href="config.php">Config</a>
						</li>
						<li class="<?php echo $v?>">
							<a href="index.php?vul=1">Vuls</a>
						</li>
                        <!--
						<li>
							<a href="api.php?type=sqlmap_update">Update</a>
						</li>
                        
                        -->
                        
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
									<a href="api.php?type=sqlmap_clear">Clear Finish</a>
								</li>
								<li>
									<a href="api.php?type=sqlmap_clearall">Clear Finish and Runing</a>
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
	
			
			<table class="table">
				<thead>
					<tr>
						<th>
							Hash
						</th>
						<th>
							Url
						</th>
						<th>
							Status
						</th>
                        <th>
							Node
						</th>
						<?php 
						if(@$_GET['vul']){
							
							echo '<th>
							DBtype
						</th>
						'
						;
						}
						?>
						<th>
							View
						</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$echo=1;
				$search="";
				if(isset($_POST['search'])){
					$search="and url like '%{$_POST['search']}%'";
				}
				$query=mysql_query("select * from sqlmap where hash!='' {$search} and userhash='{$hash}' order by pr desc");
				while($row=mysql_fetch_array($query)){
				if($row['status']=='running') {$class='active';}
				else{$class='success';}
				preg_match_all('/ => \'(.*)\n\'/',$row['data'],$match);
				$match=$match[1];
				$last=@$match[count($match)-1];
				$key=$row['key'];
				$stat=$row['status'];
				$url=$row['url'];
				$pr=$row['pr'];
				$dbtype=$row['dbtype'];
                $node=$row['apiserver'];
				if(stripos($last,"back-end") and stripos($last,"--dbms")==false){
					$class='danger';
					$stat='Inject';
					mysql_query("update sqlmap set status='{$stat}' where `key`='{$key}' and userhash='{$hash}'");
					
				}
				preg_match_all('/ => \'(.*)\n\'/',$row['data'],$match);
				$data='';
				foreach($match[1] as $m){
							$m=stripcslashes($m);
							$data.=$m."\n";
						
							
						}
					
				if(@$_GET['vul']==1 and $stat!='Inject'){
					//echo $stat."<br>";
					$echo=0;
				}else{$echo=1;}
				if($echo){
				?>
			
					<tr class="<?php echo $class?>">
						<td>
							<?php echo $key?>
						</td>
						<td>
							<?php echo substr($url,0,60)?>..
						</td>
						<td>
						
							<?php echo $stat;?>
							
						</td>
                            <td>
						
							<?php echo $node;?>
							
						</td>
						<?php
                        
                        /*
						$dircount=mysql_query("select count(*) as count from dirscan where url like '%{$host}%' and userhash='{$hash}' ");
						while($dirrow=mysql_fetch_array($dircount)){
							$dircounts=$dirrow['count'];
						}
						*/
						?>
						<?php 
                        
						if(@$_GET['vul']){
							echo "<td>{$dbtype}</td>";
							if(!@$dircounts){
								$dircounts=0;
							}
							//echo "<td>{$dircounts}</td>";
						}
						
						?>
						
						<td>
						
						
						
			<a id="modal-<?php echo $key?>" href="#modal-container-<?php echo $key?>" role="button" class="btn" data-toggle="modal">View</a>
			
			<div class="modal fade in" id="modal-container-<?php echo $key?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								×
							</button>
							<h4 class="modal-title" id="myModalLabel">
								<?php echo substr($url,0,60)?>..
							</h4>
						</div>
						<div class="modal-body">
							<textarea class="form-control" rows='1' cols='85'><?php echo $url?></textarea></br>
							
							<textarea class="form-control" rows='7' cols='85'><?php echo $row['request']?></textarea><br>
							<textarea class="form-control" rows='7' cols='85'><?php echo $data?></textarea><br>
							
						</div>
						<div class="modal-footer">
							 
							<button type="button" class="btn btn-default" data-dismiss="modal">
								Close
							</button> 
							<button type="button" class="btn btn-primary">
								Save changes
							</button>
						</div>
					</div>
					
				</div>
				
			</div>
						</td>
					</tr>
				<?php }}?>
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
