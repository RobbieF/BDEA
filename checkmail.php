<?php
/*
  This PHP script comes from a live coding session with Robbie Ferguson
  on Category5 Technology TV - Episode 278
  Check it out for the full interview with Gerold Setz and the tutorial:
  http://www.category5.tv/episodes/278.php
  
  This script is free to use, modify and distribute under the terms of the
  Creative Commons Attribution 2.5 Canada License - http://cat5.tv/license
  
  This is not a "functional" script per-se.
  Rather, it is a demonstration of what you could do with the DEA API.
  What you do with the true or false result is up to you, as explained in the video.
*/

if (isset($_POST['name']) && isset($_POST['email'])) {
  // strip malicious code & set strings
  $name  = strip_tags($_POST['name']);
  $email = strip_tags($_POST['email']);

  // check the email address
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email address is considered <font color="green">good</font>. Registration will proceed.';
  } else {
        $message = 'Email address is considered <font color="red">bad</font>. Registration will be rejected.';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
 <title>Subscription Form</title>
 <meta http-equiv="content-Language" content="en-us" />
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <META NAME="rating" CONTENT="General">
 <META NAME="ROBOTS" CONTENT="ALL">
 <meta http-equiv="imagetoolbar" content="no" />
</head>
<body>
  <form method="post">
    <input type="text" name="name" placeholder="Your Name" value="<?= $name ?>">
    <br /><input type="email" name="email" placeholder="Your Email" value="<?= $email ?>">
    <br /><input type="submit" value="Register">
  </form>
  <?php
    if (isset($message)) echo '<br /><br /><b>' . $message . '</b><br /><br />' . $summary . PHP_EOL;
  ?>
</body>
</html>
