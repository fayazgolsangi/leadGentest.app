<?php
//index.php

ob_start();
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
// select logged in users detail
$res = $conn->query("SELECT * FROM users WHERE id=" . $_SESSION['user']);
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

$error = '';
$fname = '';
$lname = '';
$email = '';
$phone = '';
#$subject = '';
#$message = '';

function clean_text($string)
{
 $string = trim($string);
 $string = stripslashes($string);
 $string = htmlspecialchars($string);
 return $string;
}

if(isset($_POST["submit"]))
{
 if(empty($_POST["fname"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your first name</label></p>';
 }
 else
 {
  $fname = clean_text($_POST["fname"]);
 }
 if(empty($_POST["lname"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your last name</label></p>';
 }
 else
 {
  $lname = clean_text($_POST["lname"]);
 }
 if(empty($_POST["email"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
 }
 else
 {
  $email = clean_text($_POST["email"]);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
   $error .= '<p><label class="text-danger">Invalid email format</label></p>';
  }
 }
 if(empty($_POST["phone"]))
 {
  $error .= '<p><label class="text-danger">Phone number is required</label></p>';
 }
 else
 {
  $phone = clean_text($_POST["phone"]);
 }
//  if(empty($_POST["message"]))
//  {
//   $error .= '<p><label class="text-danger">Message is required</label></p>';
//  }
//  else
//  {
//   $message = clean_text($_POST["message"]);
//  }

 if($error == '')
 {
  $file_open = fopen("leadsquared.csv", "a");
  date_default_timezone_set('UTC');
  $sdate = date('d-m-Y');
  $rdate = date('d-m-Y', strtotime('+2 days'));
  $no_rows = count(file("leadsquared.csv"));
  if($no_rows > 1)
  {
   $no_rows = ($no_rows - 1) + 1;
  }
  $form_data = array(
   'sr_no'  => $no_rows,
   'fname'  => $fname,
   'lname'  => $lname,
   'email'  => $email,
   'phone' => $phone,
   'sdate' => $sdate,
   'rdate' => $rdate,
   
  );
  fputcsv($file_open, $form_data);
  $error = '<label class="text-success">Thank you for submitting the lead</label>';
  $fname = '';
  $lname = '';
  $email = '';
  $phone = '';
  
 }
}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Lead Form</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
 
 <!-- Navigation Bar-->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Lead Generation Tool</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="form.php">Leads</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <span
                            class="glyphicon glyphicon-user"></span>&nbsp;Logged
                        in: <?php echo $userRow['email']; ?>
                        &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br/>
<br/>
  <div class="container">
   <div class="col-md-6" style="margin:0 auto; float:none;">
    <form method="post">
     <h3 align="center">Lead Form</h3>
     <br />
     <?php echo $error; ?>
     <div class="form-group">
      <label>First Name</label>
      <input type="text" name="fname" placeholder="First Name" class="form-control" value="<?php echo $fname; ?>" />
     </div>
     <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="lname" placeholder="Last Name" class="form-control" value="<?php echo $lname; ?>" />
     </div>
     <div class="form-group">
      <label>Enter Email</label>
      <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>" />
     </div>
     <div class="form-group">
      <label>Phone Number</label>
      <input type="text" name="phone" placeholder="Phone Number" class="form-control" value="<?php echo $phone; ?>" />
     </div>
     <div class="form-group" align="center">
      <input type="submit" name="submit" class="btn btn-info" style ="background-color:#337ab7, border-color:#2e6da4" value="Submit" />
     </div>
    </form>
   </div>
  </div>
 </body>
</html>