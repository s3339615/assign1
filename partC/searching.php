<?php
	require_once('MiniTemplator.class.php');
	require_once('database.php');
	
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on <br />' . DB_HOST . '\n';
		exit;
	}

	//echo 'Connected to mysql <br />'; //check whether connect to mysql

	if(!mysql_select_db(DB_NAME, $dbconn)) 
	{
		echo 'Could not user database <br />' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
	

	
	//echo 'Connected to database <br /> ' . DB_NAME . '\n'; //check whether connect to database
	
	$template = new MiniTemplator;
	if (!$template->readTemplateFromFile("searching.htm")) die ("MiniTemplator.readTemplateFromFile failed.");
	
	/*query for get the grape varities from database*/
    $query = 'SELECT * FROM grape_variety ORDER BY variety';
	$variery_ids = array();
	$variery_names = array();
	$varieties = mysql_query($query, $dbconn);
	while($row = mysql_fetch_row($varieties))
	{
		$template->setVariable("variery_ids", $row[0]);
		$template->setVariable("variery_names", $row[1]);
		$template->addBlock("variery");
	}
	
	/*query for get the regions from database*/
    $query = 'SELECT * FROM region ORDER BY region_name';//Get all regions
    $region_ids = array();
    $region_names = array();
    $_regions = mysql_query($query, $dbconn);
	while($row = mysql_fetch_row($_regions))
	{
		$template->setVariable("region_id", $row[0]);
		$template->setVariable("region_name", $row[1]);
		$template->addBlock("regions");

	//	echo "<option value=\"$row[0]\">$row[1]</option>\n";
                           
	//	echo '  3 region_ids <br />' .$region_ids . '\n';
	//	echo '  4 region names <br />' . $region_names . '\n';
	//	echo '  haha region row print <br />' . $row . '\n';
	}
	
	//echo '  1 region_ids <br />' .$region_ids . '\n';
	//echo '  2 region names <br />' . $region_names . '\n';
	//$template->setVariable("region_ids", $region_ids);
	//$template->setVariable("region_names", $region_names);
	//$template->setVariable("test", "test the region print.");
	

    /*query for get the years from database*/
    $query = 'SELECT DISTINCT year FROM wine ORDER BY year';
	//$year_ids = array();
	$year_names = array();
    $years = mysql_query($query, $dbconn);
    while($row = mysql_fetch_row($years))
	{
       // $template->setVariable("year_ids", $row[0]);
		$template->setVariable("year_names", $row[0]);
		$template->addBlock("year");
    }
	
    mysql_close($dbconn);
    //echo error_get_last();
	
	$template->generateOutput();
?>
