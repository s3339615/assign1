<html>

<head>
<title>Search Screen of Winestore</title>
</head>

<?php
	require_once('database.php');
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on ' . DB_HOST . '\n';
		exit;
	}
	
	//echo 'Connected to mysql <br />'; check whether connect to mysql
	
	if(!mysql_select_db(DB_NAME, $dbconn)) 
	{
		echo 'Could not user database ' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
	
	//echo 'Connected to database ' . DB_NAME . '\n'; //check whether connect to database
	
	/*query for get the grape varities from database*/
    $query = 'SELECT * FROM grape_variety ORDER BY variety';
    $varieties = mysql_query($query, $dbconn);
	
	/*query for get the regions from database*/
    $query = 'SELECT * FROM region ORDER BY region_name';//Get all regions
    $regions = mysql_query($query, $dbconn);
	
    /*query for get the years from database*/
    $yearArray = array();
    $query = 'SELECT DISTINCT year FROM wine ORDER BY year';
    $years = mysql_query($query, $dbconn);
    $x = 0;
    while($row = mysql_fetch_row($years))
	{
        $yearArray[$x] = $row[0];
        $x++;
    }
?>

<body>

<div>
	<!--Header Here-->
    <div>
        <div><I><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Screen of Winestore</h3></I></div>
    </div> 
        <div>
			<!-- GET method for search_result.php -->
            <form action="search_result.php" method="get" id="searchResult" name="searchResult">
            <input type="hidden" id="criteria" name="criteria" />
			
            <table>
				<!-- Table Row 1 -->
                <tr>
                    <td bgcolor="#D8CEF6"><b>1.&nbsp;Wine Name</b></td>
                    <td bgcolor="#F2EFFB">
                    <input type="text" name="winename" id="winename"/></td>
                </tr>
				<!-- Table Row 2 -->
                <tr>
                    <td><b>2.&nbsp;Winery Name</b></td>
                    <td>
                    <input type="text" name="wineryname" id="wineryname"/></td>
                </tr>
				<!-- Table Row 3 -->
                <tr>
                    <td bgcolor="#D8CEF6"><b>3.&nbsp;Region</b></td>
                    <td bgcolor="#F2EFFB">
                    <select name="region" id="region">
                        <?php
                            while($row = mysql_fetch_row($regions)) 
							{
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                    </select></td>
                </tr>
				<!-- Table Row 4 -->
                <tr>
                    <td><b>4.&nbsp;Grape Variety</b></td>
                    <td>
                    <select name="grapeVariety" id="grapeVariety">
                        <option value="0" selected="selected"> All Variery </option>
                        <?php
                            while($row = mysql_fetch_row($varieties)) 
							{
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                    </select></td>
                </tr>
				<!-- Table Row 5 -->
                <tr>
                    <td bgcolor="#D8CEF6"><b>5.&nbsp;Range of Years</b></td>
                    <td bgcolor="#F2EFFB">
					From
                    <select name="yearFrom" id="yearFrom">
                        <option value="0" selected="selected"> Select Year </option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) 
							{
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select>
                    to
                    <select name="yearTo" id="yearTo">
                        <option value="0" selected="selected"> Select Year </option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) 
							{
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select>
                    </td>
                </tr>
				<!-- Table Row 6 -->
                <tr>
                    <td><b>6.&nbsp;Minimum Number of wines in Stock</b></td>
                    <td><input type="text" name="min_num_instock" id="min_num_instock"/></td>
                </tr>
				<!-- Table Row 7 -->
                <tr>
                    <td bgcolor="#D8CEF6"><b>7.&nbsp;Minimum Number of wines Ordered</b></td>
                    <td bgcolor="#F2EFFB"><input type="text" name="min_num_ordered" id="min_num_ordered"/></td>
                </tr>
				<!-- Table Row 8 -->
                <tr>
                    <td><b>8.&nbsp;Dollar Cost Range</b></td>
                    <td> (MIN)$<input type="text" name="min_cost" id="min_cost" class="number" /> (MAX)$<input type="text" name="max_cost" id="max_cost"/></td>
                </tr>
				<!-- Table Row 9 "Button" -->
                <tr>
                    <td colspan="2" align="right" bgcolor="#F8E0F7"><input type="submit" name="btnSubmit" id="btnSubmit" value="Search" /></td>
                </tr>
            </table>
            </form>
        </div>       
    </div>
</body>
<?php
    mysql_close($dbconn);
?>
</html>
