<!-- SHTracker, Copyright Josh Fradley (http://github.com/joshf/SHTracker) -->
<html>
<head>
<title>SHTracker</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<!-- Change the style to match the rest of your site here -->
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<?php

//Connect to database
require_once("config.php");

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die("Could not connect: " . mysql_error());
}
    
mysql_select_db(DB_NAME, $con);

//List all downloads
if (isset($_GET["list"])) {
    $listdownloads = mysql_query("SELECT name, count FROM Data");
    echo "<h1>All Downloads</h1><p>";
    while($info = mysql_fetch_assoc($listdownloads)) {
        echo "<b>" . $info["name"] . "</b>: " . $info["count"] . "<br />";
    }
    echo "</p></body></html>";
    mysql_close($con);
    exit;
}

if (isset($_GET["id"])) {
    $id = mysql_real_escape_string($_GET["id"]);
} else {
    die("<h1>SHTracker: Error</h1><p>ID cannot be blank.</p><hr /><p><a href=\"javascript:history.go(-1)\">&larr; Go Back</a></p></body></html>");
}

//If ID exists show count or else die
$showinfo = mysql_query("SELECT name, count FROM Data WHERE id = \"$id\"");
$showresult = mysql_fetch_assoc($showinfo); 
if ($showresult != 0) { 
    if (isset($_GET["plain"])) {
        echo $showresult["count"];
    } else {
        echo "<p>" . $showresult["name"] . " has been downloaded " . $showresult["count"] . " time(s).</p>";
    }
} else { 
    die("<h1>SHTracker: Error</h1><p>ID does not exist.</p><hr /><p><a href=\"javascript:history.go(-1)\">&larr; Go Back</a></p></body></html>");
}

mysql_close($con);

?>
</body>
</html>