<?php

	require_once('MiniTemplator.class.php');
	$template = new MiniTemplator;
	if (!$template->readTemplateFromFile("view_wine_names.htm")) die ("MiniTemplator.readTemplateFromFile failed.");
	
	//start session here
	
	session_start();
	/*
    if(isset($_SESSION['searchsess'])) 
	{
        $name_of_wine = $_SESSION['searchsess'];
        $template->setVariable("name_of_wine", $name_of_wine);
    }
	*/
	if(isset($_GET['endsession'])) 
	{
        $template->setVariable("end_session", 'Your session has been destroyed and wine names has been twitted.');
        unset($_SESSION['searchsess']);
    } 
	else 
		$template->setVariable("end_session", '<a href="view_wine_names.php?endsession=yes">Session End</a>');

	$template->generateOutput();

?>
