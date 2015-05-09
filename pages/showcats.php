<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

// Are we connected to database?
if(!$getList){ die('Invalid access.');}

?>
<table width="100%"  border="1" cellpadding="3" cellspacing="0" bordercolor="#00B0B0">
  <tr>
    <td bgcolor="#E1FFFF"><font size="2"><strong>Categories</strong></font></td>
  </tr>
  <tr>
    <td><ul>
	  <font size="2"><strong>
<?php
	if($searchIn != NULL){ $addURL = '&searchTerm='.$searchTerm.'&searchIn='.$searchIn;}
	if(count($catList) > 0)
	{
		foreach($catList as $catID => $catName)
		{
			$getCount = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `blogtopics` WHERE `catID`='$catID'"));
			echo '<li><a href="?catID='.$catID.$addURL.'">'.$catName.'</a> ('.$getCount[0].') </li>';
		}
	}
	?>
      </strong></font>
    </ul></td>
  </tr>
</table>
<br />