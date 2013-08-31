<?php
	//error_reporting( E_ALL ); //error message
    $criteria = $_GET["criteria"];//all = no criteria; some = some criterias
    $winename = $_GET["winename"];
    $wineryname = $_GET["wineryname"];
    $region = $_GET["region"];
    $grapeVariety = $_GET["grapeVariety"];
    $yearFrom = $_GET["yearFrom"];
    $yearTo = $_GET["yearTo"];
    $min_num_instock = $_GET['min_num_instock'];
    $min_num_ordered = $_GET['min_num_ordered'];
    $min_cost = $_GET["min_cost"];
    $max_cost = $_GET["max_cost"];
	
	//check the validation of cost
	if($max_cost < $min_cost)
	{
		echo 'Wrong Input!';
		die();
	}
	
	//check the validation of year
	if($yearFrom > $yearTo)
	{
		echo 'Wrong Input!';
		die();
	}
	
//    echo 'here 1</br>';
	
    $query = 'SELECT DISTINCT wine.wine_id, wine_name, year, region_name, winery_name, cost, on_hand, SUM(qty) qty, SUM(price)
              FROM wine, winery, region, inventory, items, wine_variety
              WHERE wine.winery_id = winery.winery_id AND
					wine.wine_id = inventory.wine_id AND
                    wine.wine_id = items.wine_id AND
                    winery.region_id = region.region_id AND
                    wine.wine_id = wine_variety.wine_id';
					
//    echo 'here 2</br>';
	//query for all the data
    if($criteria == 'all') 
	{
        $query .= ' GROUP BY items.wine_id
                    ORDER BY wine_name, year';
    }
	//echo 'here 3</br>';
    else 
	{
	//set together with some other sql
        if($winename != '') 
		{
            $winename = str_replace("'", "''", $winename);
            $query .= " AND wine.wine_name LIKE '%$winename%'";
        }
		
	//	echo 'here 4</br>';
		
        if($wineryname != '') 
		{
            $wineryname = str_replace("'", "''", $wineryname);
            $query .= " AND winery_name LIKE '%$wineryname%'";
        }
		
	//	echo 'here 5</br>';
		
        if($region != 1) 
		{
            $query .= " AND region.region_id = $region";
        }
		
	//	echo 'here 6</br>';
		
        if($grapeVariety != 0) 
		{
            $query .= " AND variety_id = $grapeVariety";
        }
		
	//	echo 'here 7</br>';
		
        if(($yearFrom != 0) && ($yearTo != 0)) 
		{
            $query .= " AND year >= $yearFrom AND year <= $yearTo";
        } 
		
	//	echo 'here 8</br>';
		
		else if($yearFrom != 0) 
		{
            $query .= " AND year >= $yearFrom";
        } 
		
	//	echo 'here 9</br>';
		
		else if($yearTo != 0) 
		{
            $query .= " AND year <= $yearTo";
        }
		
	//	echo 'here 10</br>';
		
        if($min_num_instock != 0) 
		{
            $query .= " AND on_hand >= $min_num_instock";
        }
		
	//	echo 'here 11</br>';
		
        if($min_cost != 0) 
		{
            $query .= " AND cost >= $min_cost";
        }
		
	//	echo 'here 12</br>';
		
        if($max_cost != 0) 
		{
            $query .= " AND cost <= $max_cost";
        }
		
	//	echo 'here 13</br>';
		
        if($min_num_ordered != 0) 
		{
            $query .= " GROUP BY items.wine_id
                        HAVING qty >= $min_num_ordered
                        ORDER BY wine_name, year";
        }
		
	//	echo 'here 14</br>';
		
        else $query .= ' GROUP BY items.wine_id
                         ORDER BY wine_name, year';
						 
    //    echo 'here 15</br>';
		
    //    echo $query . '</br>' ; //debug info
		
	//	echo 'here 16</br>';
		
    }
	//	echo 'here 17</br>';
	//	echo $query . '</br>' ;	
	
	/*
	* start connect to the winestore database
	* start use the MiniTemplator
	*/
	require_once('MiniTemplator.class.php');	
    require_once('database.php');
	
	//new the MiniTemplator and read the search_result.htm
	$template = new MiniTemplator;
	if (!$template->readTemplateFromFile("search_result.htm")) die ("MiniTemplator.readTemplateFromFile failed.");
	//echo 'Connected to search_result.htm <br />';
	
	//$template->setVariable("display", "test the info");
	
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on ' . DB_HOST . '\n';
		exit;
	}
	//echo 'Connected to mysql <br />';

	if(!mysql_select_db(DB_NAME, $dbconn)) 
	{
		echo 'Could not user database ' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
	//echo 'Connected to database ' . DB_NAME . '\n';
    
    $result = mysql_query($query, $dbconn);
    if(!$result) 
	{
        echo "Wrong query string! [$query]";
        exit;
    }
	
	//$error_info = array();
	if(mysql_num_rows($result) == 0)
	{
		$template->setVariable("error_info", "No records match your search criteria.");
	}
	
	//print the selected by searching.php
	//echo $query . '</br>' ;	
	$wine = array();
	$grape_variety = array();
    while($row = mysql_fetch_row($result)) 
	{
	//	echo 'while 1 </br>';
        $query = "SELECT variety FROM wine_variety, grape_variety
                  WHERE wine_variety.wine_id = $row[0] AND
                  wine_variety.variety_id = grape_variety.variety_id
                  ORDER BY variety";
				  
	//	echo $row . 'row 1</br>';
	//	echo $wine . 'wine print</br>';
		
        $varieties = mysql_query($query, $dbconn);
		
	//	echo 'while 2 </br>';
		
        $str = "";
		//it may get the other grape variety with same id
        while($variety = mysql_fetch_row($varieties)) 
		{
            $str .= "$variety[0], ";
	//		echo $variety . 'varitery print</br>';
	//		echo 'while 3 </br>';
			
        }
	//	echo $row . 'row 2</br>';
		
		$grape_variety = substr($str, 0, strlen($str)-2);
		
	//	echo 'while 4 </br>';
		
		$template->setVariable("wineName", $row[1]);
	//	echo $row[1];
		
		$template->setVariable("year", $row[2]);
	//			echo $row[3];
		
		$template->setVariable("winery", $row[3]);
	//	echo $row[4];
		
		$template->setVariable("region", $row[4]);
	//	echo $row[5];
		
		$template->setVariable("cost", $row[5]);
	//	echo $row[6];
		
		$template->setVariable("numberAva", $row[6]);
	//	echo $row[7];
		
		$template->setVariable("stock", $row[7]);
	//	echo $row[8];
		
		$template->setVariable("sales", $row[8]);
	//	echo $row[9];		
		//echo $row[1];
		$template->setVariable("grape_variety", $grape_variety);
		$template->addBlock("printinfo");
		
		
		
    }
	
	
    mysql_close($dbconn);
    //echo error_get_last();
	
	$template->generateOutput();
	
?>


