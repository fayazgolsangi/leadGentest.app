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
$PID = '';
$Aname = '';
$Status = '';
$Discription = '';
$fdate = '';
$ldate = '';
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
 if(empty($_POST["PID"]))
 {
  $error .= '<p><label class="text-danger">Invalid Pid</label></p>';
 }
 else
 {
  $PID = clean_text($_POST["PID"]);
 }
 if(empty($_POST["Aname"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your Apartment name</label></p>';
 }
 else
 {
  $Aname = clean_text($_POST["Aname"]);
 }
//  if(empty($_POST["email"]))
//  {
//   $error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
//  }
//  else
//  {
//   $email = clean_text($_POST["email"]);
//   if(!filter_var($email, FILTER_VALIDATE_EMAIL))
//   {
//    $error .= '<p><label class="text-danger">Invalid email format</label></p>';
//   }
//  }
 if(empty($_POST["Status"]))
 {
  $error .= '<p><label class="text-danger">Please update the status</label></p>';
 }
 else
 {
  $Status = clean_text($_POST["Status"]);
 }
//  if(empty($_POST["message"]))
//  {
//   $error .= '<p><label class="text-danger">Message is required</label></p>';
//  }
//  else
//  {
//   $message = clean_text($_POST["message"]);
//  }
 
 if(empty($_POST["Discription"]))
 {
  $error .='<p><label class="text-danger">Please update the discription</label></p>';
 } 
else
 {
  $Discription = clean_text($_POST["Discription"]);
 }

 if(empty($_POST["fdate"]))
 {
  $error .='<p><label class="text-danger">Please update the followup date</label></p>';
 } 
else
 {
  $fdate = clean_text($_POST["fdate"]);
 }

 if(empty($_POST["ldate"]))
 {
  $error .='<p><label class="text-danger">Please update the date</label></p>';
 } 
else
 {
  $ldate = clean_text($_POST["ldate"]);
 }


 if($error == '')
 {
  $file_open = fopen("output.csv", "a");
  #date_default_timezone_set('UTC');
  #$sdate = date('d-m-Y');
  #$rdate = date('d-m-Y', strtotime('+2 days'));
  $no_rows = count(file("output.csv"));
  if($no_rows > 1)
  {
   $no_rows = ($no_rows - 1) + 1;
  }
  $form_data = array(
   'sr_no'  => $no_rows,
   'PID'  => $PID,
   'Aname'  => $Aname,
   #'email'  => $email,
   'Status' => $Status,
   'Discription' => $Discription,
   'fdate' => $fdate,
   'ldate' => $ldate,
   
  );
  fputcsv($file_open, $form_data);
  $error = '<label class="text-success">Thank you for submitting the lead</label>';
  $PID = '';
  $Aname = '';
  #$email = '';
  $Status = '';
  $Discription = '';
  $fdate = '';
  $ldate = '';
  
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
      <label>PID</label>
      <input type="text" name="PID" placeholder="PID" class="form-control" value="<?php echo $PID; ?>" />
     </div>
     <div class="form-group">
      <label>Apt Name</label>
      <input type="text" name="Aname" placeholder="Apt Name" class="form-control" value="<?php echo $Aname; ?>" />
     </div>
     <!-- <div class="form-group">
      <label>Email</label>
      <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" />
     </div> -->
     <div class="form-group">
      <label>Status</label>
      <input type="text" name="Status" class="form-control" placeholder="Status" value="<?php echo $Status; ?>" />
     </div>
     <div class="form-group">
      <label>Discription</label>
      <input type="text" name="Discription" placeholder="Discription" class="form-control" value="<?php echo $Discription; ?>" />
     </div>
     <div class="form-group">
      <label>Followup Date</label>
      <input type="text" name="fdate" placeholder="Followup Date" class="form-control" value="<?php echo $fdate; ?>" />
     </div>
     <div class="form-group">
      <label>Date</label>
      <input type="text" name="ldate" placeholder="Date" class="form-control" value="<?php echo $ldate; ?>" />
     </div>
     <div class="form-group" align="center">
      <input type="submit" name="submit" class="btn btn-info" style ="background-color:#337ab7, border-color:#2e6da4" value="Submit" />
     </div>
    </form>
   </div>
  </div>
 </body>
</html>