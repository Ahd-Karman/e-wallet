<?php

/* Not Work !


include('header.php');
$db = new DBController();
class User {
    public $user ;
	public $email ;
	public $password1 ;
	public $password2 ;
 
     function add_user(){
         $sql="INSERT INTO user (name,EMAIL,PASSWORD) VALUES ('$name','$email','$password1') ";
                                         
          if(mysqli_query($db->connectDB(),$this-> $sql))
           {
             echo "<div class='alert alert-success'>One Row Inserted </div>";
           }
           else
             {
               echo("Error description: " . mysqli_error($con));
             }
     }
 }
*/ 

?>