<?php
session_start();
require_once("./navbar.php");
require_once("./config.php");
// echo $_GET['uid'];
// $_SESSION['callingPage'] = "notifications.php?uid=".$_GET['uid'];
?>

<html>
	<head>
		<title>Notifications</title>
	</head>
	<script>
		$(document).ready(function(){
    		$('.modal').modal();
  		});
	</script>
	<style>
		.card{
			background-color: whitesmoke;
			/* height: 130px; */
		}
		p{
			color: black;
		}
		.accept, .reject{
    		margin: 10px;
    		background-color: salmon;
		}
		.accept:hover, .reject:hover{
    		background-color: tomato;
		}
		review{
			color: black;
		}
		.review:hover{
    	/* color: #4d6d9a; */
    	color: salmon;
    	/* border-bottom: 2px solid #4d6d9a; */
    	border-bottom: 2px solid salmon;
		}
		.view{
			margin-top: 25px;
			margin-right: 25px;
		}
	</style>
	<body>
		<div class="container">
			<?php
				$query = "SELECT requsername, bookname, status, forsale, forrent, userid, requserid, bid, email, phone from notifications inner join users on requserid=uid where userid=".$_GET['uid'].";";
				$result = pg_query($db_connection, $query);
				// $insideq = "SELECT email, phone from users where uid=".$row[6].";";
				// $insider = pg_query($db_connection, $insideq);
				// $insiderow = pg_fetch_row($insider);
				if(pg_num_rows($result)>0){
					echo '<h4>Notifications</h4>';
					while($row = pg_fetch_row($result)){
						// $insideq = "SELECT email, phone from users where uid=".$row[6].";";
						// $insider = pg_query($db_connection, $insideq);
						// $insiderow = pg_fetch_row($insider);
						echo '<div class="row">
    						
      							<div class="card row z-depth-1 col s12">
        							<div class="card-content white-text col s8">
          							<p>
										<b>'.$row[0].'</b> has requested to ';
									if($row[3]){
										echo 'buy';
									}
									else{
										echo 'rent';
									}
									echo ' <b>'.$row[1].'</b>
									<br>Status: ';
									if($row[2]==1){
										echo 'Accepted</p></div>
										
          								<a href="#modal1" class="btn-flat review view modal-trigger right" >View Contact Details</a>
        								';
									}
									else if($row[2]==0){
										echo 'Rejected</p></div>';
									}
									else{
										echo 'Pending</p></div>
										
											<a href="profile.php?uid='.$row[6].'" class="btn-flat review view right">View Profile</a>
										<div class="card-action col s12">
          									<a href="updatenotif.php?uid='.$row[5].'&requserid='.$row[6].'&bid='.$row[7].'&status=1" class="btn accept">Accept</a>
          									<a href="updatenotif.php?uid='.$row[5].'&requserid='.$row[6].'&bid='.$row[7].'&status=0" class="btn reject">Reject</a>
											
        								</div>';
									}
									echo '</div>
  						</div>';
						// echo $row[6];
						// while($insiderow = pg_fetch_row($insider)){
						echo '<div id="modal1" class="modal">
    						<div class="modal-content">
      							<h4>'.$row[0].'</h4>
      							<p>Email Address: '.$row[8].'<br>Phone Number: '.$row[9].'</p>
    						</div>
    						<div class="modal-footer">
      							<a href="" class="btn modal-close accept">Close</a>
    						</div>
  						</div>';
						// }
					}
				}
				else{
					echo '<h4>No Notifications</h4>';
				}

				//own requests
				$query1 = "SELECT username, bookname, status, forsale, forrent, userid, requserid, bid from notifications where requserid=".$_GET['uid'].";";
				$result1 = pg_query($db_connection, $query1);
				if(pg_num_rows($result1)>0){
					echo '<h4>Requested Notifications</h4>';
					while($row1 = pg_fetch_row($result1)){
						$insideq = "SELECT email, phone from users where uid=".$row1[6].";";
						$insider = pg_query($db_connection, $insideq);
						// $insiderow = pg_fetch_row($insider);
						echo '<div class="row">
      							<div class="card row z-depth-1 col s12">
        							<div class="card-content white-text col s8">
          							<p>
										<b>You </b> have requested to ';
									if($row1[3]){
										echo 'buy';
									}
									else{
										echo 'rent';
									}
									echo ' <b>'.$row1[1].'</b> from <b>'.$row1[0].'</b>
									<br>Status: ';
									if($row1[2]==1){
										echo 'Accepted</p></div>
										
          								<a href="#modal1" class="btn-flat review view modal-trigger right" >View Contact Details</a>';
									}
									else if($row1[2]==0){
										echo 'Rejected</p></div>';
									}
									else{
										echo 'Pending</p></div>
										
											<a href="profile.php?uid='.$row1[5].'" class="btn-flat review view right">View Profile</a>';
									}
									echo '</div>
  						</div>';
						// echo $row1[6];
						while($insiderow = pg_fetch_row($insider)){
						echo '<div id="modal1" class="modal">
    						<div class="modal-content">
      							<h4>'.$row1[0].'</h4>
      							<p>Email Address: '.$insiderow[0].'<br>Phone Number: '.$insiderow[1].'</p>
    						</div>
    						<div class="modal-footer">
      							<a href="" class="btn modal-close accept">Close</a>
    						</div>
  						</div>';
						}
					}
				}
				else{
					echo '<h4>No Notifications</h4>';
				}

			?>
		</div>
	</body>
</html>
<?php
require_once("./footer.php");
?>