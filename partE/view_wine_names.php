<?php

	require_once('MiniTemplator.class.php');
//	require_once('twitteroauth.php');
	$template = new MiniTemplator;
	if (!$template->readTemplateFromFile("view_wine_names.htm")) die ("MiniTemplator.readTemplateFromFile failed.");
	
//	$consumer_key = 'vtPd9aIPLigp0Bu1I3keA';
//	$consumer_secret = 'nI2dWa3qIAQ03kejgaRtUSeOjhj38iSxcWKjuAYfI';
//	$access_token = '1715913469-tYYIzcxgRD3NOANYzbvtLc58ou8E40NYTjZHL43';
//	$access_token_secret = 'FzQMVIr2lt62xH1Z8U1OF9en6ZzzQZuric22bsuhdIg';
	
	//start session here
	
	session_start();
	
	 //try to get the wine name here.
	 if(isset($_SESSION['nameofwines']))
	 { 
		
		$name_of_wine = $_SESSION['nameofwines'];
		foreach($name_of_wine as $list_names)
		{	
			
			$template->setVariable("list_names", $list_names);
			$template->addBlock("printwinename");
		}
		
	}
	//end the session and push into the twitter
	if(isset($_GET['endsession'])) 
	{
        $template->setVariable("end_session", "Destory the session and push result in Twitter.");
	//	$twitter_connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        unset($_SESSION['searchsess']);
    } 
	else 
		$template->setVariable("end_session", "<a href='view_wine_names.php?endsession=yes'>Session End</a>");
		
	$template->generateOutput();


?>
