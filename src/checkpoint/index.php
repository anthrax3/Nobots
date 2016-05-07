<?php

$RECAPTCHA_SECRET = 'Insert your reCAPTCHA secret';
$RECAPTCHA_SITEKEY = 'Your site key for reCAPTCHA';
$NOBOTS_KEY = 'A secure passphrase to encrypt data with. Do not share this password with anyone';

$cssver = 3;
if (strtoupper($_SERVER['REQUEST_METHOD']) != "POST") {
?>
<!DOCTYPE html>
<html>
<head>
<title>No bots welcome!</title>
<link href='//fonts.googleapis.com/css?family=Exo+2:300,400,700' rel='stylesheet' type='text/css'>
<link href="../style.css?<?php echo $cssver; ?>" rel="stylesheet" type="text/css">
<script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
<script type="text/javascript">
function proceed() {
    document.getElementById("nobotform").submit();
}
function fillHash() {
    document.getElementById("protectvalue").value = location.hash.substr(1);
}
</script>
</head>
<body onload="fillHash();">
<div class="c">
<h1>No bots welcome!</h1>
<p>We need to prove that you're not a bot.<br />Please solve the captcha below to prove your existence.</p>
<form id="nobotform" action="" method="post"><input type="hidden" id="protectvalue" name="nobots" />
<div class="iwrap"><div class="g-recaptcha center" data-sitekey="<?php echo $RECAPTCHA_SITEKEY; ?>" data-callback="proceed"></div></div>
</form>
<p class="footer">Do not submit private or confidential information using this form. We are<br />not responsible for your data. All submitted data is to be considered public.</p>
</div>
<a href="https://github.com/bilde2910/Nobots"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
</body>
</html>
<?php
} else {
    $decvalue = $_POST["nobots"];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $RECAPTCHA_SECRET, 'response' => $_POST["g-recaptcha-response"], 'remoteip' => (!isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_CF_CONNECTING_IP"]));

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $capthres = json_decode($result, true);
    
    if ($capthres["success"] != true) {
        header("303 See Other");
        header("Location: ./#$decvalue");
        exit;
    }
    
    $decvalue = urldecode($decvalue);
    $decarr = split(";", $decvalue);
    
    $ok = false;
    
    if (count($decarr) == 3) {
        $decarr[0] = base64_decode($decarr[0]);
        $decarr[1] = base64_decode($decarr[1]);
        $decvalue = openssl_decrypt($decarr[0], "aes-256-cbc", $NOBOTS_KEY, 0, $decarr[1]);
        
        if ($decvalue !== FALSE) {
            $ok = true;
        }
    }
    
    if (!$ok) { ?>

<!DOCTYPE html>
<html>
<head>
<title>No bots welcome!</title>
<link href='//fonts.googleapis.com/css?family=Exo+2:300,400,700' rel='stylesheet' type='text/css'>
<link href="../style.css?<?php echo $cssver; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="c">
<h1>Oh no!</h1>
<p>The link you were given doesn't work!<br />You can always try asking for another one :-)</p>
<p class="footer">Do not submit private or confidential information using this form. We are<br />not responsible for your data. All submitted data is to be considered public.</p>
</div>
<a href="https://github.com/bilde2910/Nobots"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
</body>
</html>

<?php exit; }
    
    
?>
<!DOCTYPE html>
<html>
<head>
<title>No bots welcome!</title>
<link href='//fonts.googleapis.com/css?family=Exo+2:300,400,700' rel='stylesheet' type='text/css'>
<link href="../style.css?<?php echo $cssver; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="c">
<h1>Thanks for proving you're human!</h1>
<p>Here's the text you're looking for:</p>
<div class="iwrap"><div class="iinner"><div class="caret">&gt;</div><input onclick="this.select();" type="text" readonly id="nobots" value="<?php echo $decvalue; ?>" /><div class="caret">&#x2714;</div></div></div>
<p class="footer">Do not submit private or confidential information using this form. We are<br />not responsible for your data. All submitted data is to be considered public.</p>
</div>
<a href="https://github.com/bilde2910/Nobots"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
</body>
</html>
<?php
}
?>
