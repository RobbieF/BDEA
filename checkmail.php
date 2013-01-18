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
function checkmail($email) {
  $domain = array_pop(explode('@', $email));
  
  $key = '88af6a47e353c3d43d3ce9b7b9c68d10'; // put your API Key here. Get your API key via http://cat5.tv/badmail
  // IMPORTANT: if you add your API key above and the API returns "fail_key_low_credits" (do a print_r() as shown in the video)
  //            it probably means your new API key hasn't propagated the system yet.  Pour a coffee; it'll work in a few minutes.
  
  $request = 'http://check.block-disposable-email.com/easyapi/json/' . $key . '/' . $domain;
  
  $response = file_get_contents($request);
  
  $dea = json_decode($response);
  
  if ($dea->request_status == 'success') {
    if ($dea->domain_status == 'block') {
      //Access Denied
      return false;
    } else {
      // Access Granted
      return true;
    }
  } else {
    // something else went wrong with the address (maybe a malformed domain)
    return false;
  }
}

if (isset($_POST['name']) && isset($_POST['email'])) {
  // strip malicious code & set strings
  $name  = strip_tags($_POST['name']);
  $email = strip_tags($_POST['email']);

  // check the email address
  // Note: during the live show, we used && checkmail($email), which works, but I've since improved it slightly
  //       so the API will NOT be queried if the email address fails normal PHP validation.
  //       This will reduce your API calls.
  // Because this is ONLY AN EXAMPLE, you can run through as many checks as you want before running checkmail() - eg., test to ensure the domain is real (has a DNS record)
  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      if (checkmail($email)) { // note: now we are ONLY using checkmail() if the email address already passed FILTER_VALIDATE_EMAIL
        // in this instance, the email address passes both PHP's check and checkmail() - it is most likely not a Disposable Email Account
        $message = 'Email address is considered <font color="green">good</font>. Registration will proceed.';
        $summary .= 'In this instance, the email address passes both PHP\'s check and checkmail() - it is most likely not a Disposable Email Account';
      } else {
        // in this instance, PHP said the address was good, but checkmail() (the API) said it is a Disposable Email Account
        $message = 'Email address is considered <font color="red">bad</font>. Registration will be rejected.';
        $summary .= 'In this instance, PHP said the address was good, but checkmail() (the API) said it is a Disposable Email Account';
      }
    } else {
      // in this instance, the email address failed PHP's normal email address validity check (eg., malformed email address submitted)
      // the email address was never passed to checkmail() and so it doesn't count against your monthly quota for the API
      $message = 'Email address is considered <font color="red">bad</font>. Registration will be rejected.';
      $summary .= 'In this instance, the email address failed PHP\'s normal email address validity check (eg., malformed email address submitted)... the email address was never passed to checkmail() and so it doesn\'t count against your monthly quota for the API';
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
