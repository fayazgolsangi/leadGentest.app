<?php
//index.php

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
   #'message' => $message
  );
  fputcsv($file_open, $form_data);
  $error = '<label class="text-success">Thank you for submitting the lead</label>';
  $fname = '';
  $lname = '';
  $email = '';
  $phone = '';
  #$message = '';
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
     <!-- <div class="form-group">
      <label>Enter Subject</label>
      <input type="text" name="subject" class="form-control" placeholder="Enter Subject" value="<?php echo $subject; ?>" />
     </div> -->
     <!-- <div class="form-group">
      <label>Enter Message</label>
      <textarea name="message" class="form-control" placeholder="Enter Message"><?php echo $message; ?></textarea>
     </div> -->
     <div class="form-group" align="center">
      <input type="submit" name="submit" class="btn btn-info" style ="background-color:#337ab7, border-color:#2e6da4" value="Submit" />
     </div>
    </form>
   </div>
  </div>
 </body>
</html>