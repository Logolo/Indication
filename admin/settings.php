<?php

//SHTracker, Copyright Josh Fradley (http://sidhosting.co.uk/projects/shtracker)

require_once("../config.php");

$uniquekey = UNIQUE_KEY;

session_start();
if (!isset($_SESSION["is_logged_in_" . $uniquekey . ""])) {
    header("Location: login.php");
    exit; 
}

//Get current settings
$currentadminuser = ADMIN_USER;
$currentadminemail = ADMIN_EMAIL;
$currentadminpassword = ADMIN_PASSWORD;
$currentwebsite = WEBSITE;
$currentpathtoscript = PATH_TO_SCRIPT;
$currentcountuniqueonlystate = COUNT_UNIQUE_ONLY_STATE;
$currentcountuniqueonlytime = COUNT_UNIQUE_ONLY_TIME;
$currentadcode = htmlspecialchars_decode(AD_CODE); 

if (isset($_POST["Save"])) {

//Get new settings from POST
$adminuser = $_POST["adminuser"];
$adminemail = $_POST["adminemail"];
$adminpassword = $_POST["adminpassword"];
if ($adminpassword != $currentadminpassword) {
    $adminpassword = sha1($adminpassword);
}
$website = $_POST["website"];
$pathtoscript = $_POST["pathtoscript"];
$countuniqueonlystate = $_POST["countuniqueonlystate"];
if (isset($_POST["countuniqueonlytime"])) {
    $countuniqueonlytime = $_POST["countuniqueonlytime"];
}
if (isset($_POST["adcode"])) {
    if (get_magic_quotes_gpc()) {
        $adcode = stripslashes(htmlspecialchars($_POST["adcode"]));
    } else {
        $adcode = htmlspecialchars($_POST["adcode"]);
    }
}

//Remember previous settings
if (empty($adcode)) {
    $adcode = $currentadcode;
}
if (empty($countuniqueonlytime)) {
    $countuniqueonlytime = $currentcountuniqueonlytime;
}

$settingsstring = "<?php

//Database Settings
define(\"DB_HOST\", \"" . DB_HOST . "\");
define(\"DB_USER\", \"" . DB_USER . "\");
define(\"DB_PASSWORD\", \"" . DB_PASSWORD . "\");
define(\"DB_NAME\", \"" . DB_NAME . "\");

//Admin Details
define(\"ADMIN_USER\", \"$adminuser\");
define(\"ADMIN_EMAIL\", \"$adminemail\");
define(\"ADMIN_PASSWORD\", \"$adminpassword\");

//Other Settings
define(\"WEBSITE\", \"$website\");
define(\"PATH_TO_SCRIPT\", \"$pathtoscript\");
define(\"COUNT_UNIQUE_ONLY_STATE\", \"$countuniqueonlystate\");
define(\"COUNT_UNIQUE_ONLY_TIME\", \"$countuniqueonlytime\");
define(\"UNIQUE_KEY\", \"$uniquekey\");
define(\"AD_CODE\", \"$adcode\");

?>";

//Write config
$configfile = fopen("../config.php", "w");
fwrite($configfile, $settingsstring);
fclose($configfile);

//Show updated values
header("Location: " . $_SERVER["REQUEST_URI"] . "");

}
 
?>
<html>
<head>
<title>SHTracker: Settings</title>
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
<h1>SHTracker: Settings</h1>
<form method="post">
<p><b>Admin Details:</b></p>
User: <input type="text" name="adminuser" value="<? echo $currentadminuser; ?>" /><br />
Email: <input type="text" name="adminemail" value="<? echo $currentadminemail; ?>" /><br />
Password: <input type="password" name="adminpassword" value="<? echo $currentadminpassword; ?>" /><br />
<p><b>Other settings:</b></p>
Website Name: <input type="text" name="website" value="<? echo $currentwebsite; ?>" /><br />
Path to Script: <input type="text" name="pathtoscript" value="<? echo $currentpathtoscript; ?>" /><br />
<p><b>Ad Code:</b></p>
<p>Show an advert before user can continue to their download. This can be changed on a per download basis.</p>
<p><textarea cols="80" rows="8" name="adcode"><? echo $currentadcode; ?></textarea></p>
<p><b>Count Unique Visitors Only:</b></p>
<p>This settings allows you to make sure an individual users clicks are only counted once.</p>
<?php
if ($currentcountuniqueonlystate == "Enabled" ) {
    echo "<p>Hours to consider a user unique: <input type=\"text\" name=\"countuniqueonlytime\" value=\"$currentcountuniqueonlytime\" /></p>
    <input type=\"radio\" name=\"countuniqueonlystate\" value=\"Enabled\" checked/> Enabled<br />
    <input type=\"radio\" name=\"countuniqueonlystate\" value=\"Disabled\" /> Disabled";
} else {
    echo "<input type=\"radio\" name=\"countuniqueonlystate\" value=\"Enabled\" /> Enabled<br />
    <input type=\"radio\" name=\"countuniqueonlystate\" value=\"Disabled\" checked/> Disabled";
}
?>
<p><input type="submit" name="Save" value="Save" /></p>
</form>
<hr />
<p><b>Advanced Options:</b></p>
<p><i>Do not use these options unless you know what you are doing!</i></p>
<form action="actions/advanced.php" method="post">
<p>To perform any of these actions enter your admin password.</p>
<p>Password: <input type="password" name="password" /></p>
<input type="submit" name="command" value="Reset All Counts to Zero" /><br />
<input type="submit" name="command" value="Delete All Downloads" />
</form>
<hr />
<p><a href="../admin">&larr; Go Back</a></p>
</body>
</html>