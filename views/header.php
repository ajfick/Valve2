<DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $pageName . ' '; ?>| Valve 2.0</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="http://valve.gtaog.intra/js/tablesorter/jquery.tablesorter.js"></script>
    <script src="http://valve.gtaog.intra/js/tablesorter/jquery.tablesorter.widgets.js"></script>
    <script src="http://valve.gtaog.intra/js/tablesorter/widget-pager.min.js"></script>
    <link rel="stylesheet" href="http://valve.gtaog.intra/js/tablesorter/theme.dropbox.css">
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- Custom CSS / JS -->
    <link rel="stylesheet" href="http://valve.gtaog.intra/style.css">
    <script src="http://valve.gtaog.intra/js/app.js"></script>
  </head>
  <header>
    <!--- Get Git Tags to display version number --->
    <?php $commitVersion = trim(exec('git describe --tags --abbrev=0'));
    echo "<a class='branding' href='/index.php'><h1 style='display: inline;'>Valve </h1><p style='display: inline;'>{$commitVersion}</p></a>"?>
	<!--- Create Navigation --->
    <ul class="nav">
      <a href="http://valve.gtaog.intra/index.php"><li>Home</li></a>
      <a href="http://valve.gtaog.intra/views/instructions.php"><li>Instructions</li></a>
      <a href="http://valve.gtaog.intra/views/settings.php"><li>Settings</li></a>
      <?php if(isset($_SESSION['user'])){
      echo '<a href="http://valve.gtaog.intra/views/logout.php"><li>Logout</li></a>';}?>
    </ul>
  </header>
  <body>
