<?php

$NOBOTS_KEY = 'A secure passphrase to encrypt data with. Do not share this password with anyone';
$NOBOTS_CHECKPOINT_URL = 'https://apps.varden.info/nobots/checkpoint/'; // The checkpoint directory of wherever you choose you install Nobots. 

$cssver = 3;
if (strtoupper($_SERVER['REQUEST_METHOD']) != "POST") {
?>
<!DOCTYPE html>
<html>
<head>
<title>No bots welcome!</title>
<link href='//fonts.googleapis.com/css?family=Exo+2:300,400,700' rel='stylesheet' type='text/css'>
<link href="style.css?<?php echo $cssver; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="c">
<h1>No bots welcome!</h1>
<p>Hides data from bots. Plain and simple.<br />Enter your text below, and click on the lock to encode it.</p>
<form action="" method="post">
<div class="iwrap"><div class="iinner"><div class="caret">&gt;</div><input type="text" id="nobots" name="nobots" placeholder="Your text here..." maxlength="100" /><input type="submit" id="makeithappen" value="&#128274;" /></div></div>
</form>
<p class="footer">Do not submit private or confidential information using this form. We are<br />not responsible for your data. All submitted data is to be considered public.</p>
</div>
<a href="https://github.com/bilde2910/Nobots"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
</body>
</html>
<?php
} else {
    if (!isset($_POST["nobots"]) || strlen($_POST["nobots"]) > 100) {
        header("303 See Other");
        header("Location: ./");
        exit;
    }
    
    $iv = openssl_random_pseudo_bytes(16);
    $ciph = rtrim(base64_encode(openssl_encrypt($_POST["nobots"], "aes-256-cbc", $NOBOTS_KEY, 0, $iv)), "=");
    $cipher = $ciph.";".rtrim(base64_encode($iv), "=").";0";
?>
<!DOCTYPE html>
<html>
<head>
<title>No bots welcome!</title>
<link href='//fonts.googleapis.com/css?family=Exo+2:300,400,700' rel='stylesheet' type='text/css'>
<link href="style.css?<?php echo $cssver; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="c">
<h1>Thanks for fighting the bots!</h1>
<p>Here is your bot-proof URL:</p>
<div class="iwrap"><div class="iinner"><div class="caret">&gt;</div><input onclick="this.select();" type="text" readonly id="nobots" value="<?php echo $NOBOTS_CHECKPOINT_URL . bin2hex(openssl_random_pseudo_bytes(4)); ?>#<?php echo urlencode($cipher); ?>" /><div class="caret">&#x2714;</div></div></div>
<p class="footer">Do not submit private or confidential information using this form. We are<br />not responsible for your data. All submitted data is to be considered public.</p>
</div>
<a href="https://github.com/bilde2910/Nobots"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
</body>
</html>
<?php
}
?>
