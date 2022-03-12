<?php

session_start() ; 

$custom='<link rel="stylesheet" href="css/custom.css">';
include('header.php');
$db = new DBController();
?>
 <div id="fullscreen_bg" class="fullscreen_bg">
 <form action="<?php echo $_SERVER['PHP_SELF']?>" class="form-signin" method="post" style="margin: inherit;margin-top: 80px;">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
        <div class="panel panel-primary">
        
            <h3 class="text-center">
                        Create Account</h3>
        
        <div class="panel-body">   
        
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span>
                            </span>
                            <input type="text" class="form-control" name="username" placeholder="username" />
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="email" class="form-control" name='useremail' placeholder="Email"  />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name='password1' class="form-control" placeholder="New Password" />
                        </div>
                    </div>
					
					<div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name='password2' class="form-control" placeholder="Confirm Password" />
                        </div>
                    </div>
					
					
                        <input class="btn btn-lg btn-primary btn-block" type="submit" value='Create' name='save' style="background-color:deepsteelblue;" >
      </div>
       </div>
	   
	   <?php
	   
	   if(isset($_POST['save'])) {
				$user = $_POST['username'] ;
				$email = $_POST['useremail'] ;
				$password1 = $_POST['password1'] ;
				$password2 = $_POST['password2'] ;
				
				
				// check validation
				
				if (empty($user) || empty($email) || empty($password1) || empty($password2 ))
					echo " <i class='alert alert-danger'  align='center'> ERROR :  FIELDS CANNOT BE EMPTY !!! </i> " ; 
				
				else if ( strcmp( $password1 , $password2 ) > 0  ||  strcmp( $password1 , $password2 ) < 0 ) 
					echo "<i class='alert alert-danger'  align='center'> Passwords Don't Match  </i>" ;
				 else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					 echo "<i class='alert alert-danger'  align='center'> Invalid Email address </i>" ;
				//
				else {
					$sql = "INSERT INTO user (name,EMAIL,PASSWORD) VALUES ('$user',$email,'$password1') " ;
                    $db->runQuery($sql);
					
					
					//check if user added in DB
					if($db->numRows($sql)) {
					     echo "<i class='alert alert-success'  align='center'> User Added Successfully ^_^ !! </i>" ;
						 
						// code to notify admin  
					}
					
				}
	   }
			
	   ?>
        </div>
    </div>
</div>
</form>
