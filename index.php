<?php
	if (isset($_GET['Submit']))
	{
	include('agent_ransack.php');

	$AR = new phpAgentRansack();


     $AR->AgentR_Path = 'C:\Program Files\Mythicsoft\Agent Ransack\AgentRansack.exe';
	$AR->option_Directory = $_GET['rootsearch'];
	 $AR->option_Search = $_GET['search'];
	 $AR->option_SubFolders = $_GET['subdir'];
	$AR->execute();
	//echo $AR->FullCMD;
	//echo $AR->Current_Output_File;
	}
	else if (isset($_GET['refresh']))
	{
	
		$fn = $_GET['fn'];
		$count = $_GET['refresh'] -1;
		if ($count > 0)
		{	
			echo "<meta http-equiv=\"refresh\" content=\"5;url=index.php?refresh=$count&fn=$fn\">";
		}
		$outputfile = file( "$fn");
		echo "<table>";
		foreach ($outputfile as $L)
		{
			$Line = explode("\t", $L);
			echo "<TR><TD>$Line[0]</TD><TD>$Line[1]</TD><TD>$Line[2]</TD></tr>\n";
			//$L = str_replace('
			//echo "<BR>$L	";
		}	
				echo "</table>";
	 exit;
	}
?>


<h2>Agent Ransack </h2>
<form name="form1" method="get" action="index.php">
  <table width="90%"  border="1" cellspacing="2" cellpadding="2">
    <tr>
      <th width="27%" scope="col"><strong>Search For Files like (* ok) </strong></th>
      <th width="38%" scope="col"><div align="left">
        <input name="search" type="text" id="search" size="50">
      </div></th>
      <th width="35%" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td><strong>Begin Search in this directory </strong></td>
      <td><input name="rootsearch" type="text" id="rootsearch" value="c:\" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Search Sub Directories </strong></td>
      <td><input name="subdir" type="text" id="subdir" value="/s" size="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Display Results Below </strong></td>
      <td><input name="checkbox" type="checkbox" value="checkbox" checked></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Directory to Place Result File </strong></td>
      <td><input name="rootoutput" type="text" id="rootoutput" value="c:\" size="50"></td>
      <td><input type="submit" name="Submit" value="Submit"></td>
    </tr>
  </table>

</form>
<p>&nbsp;</p>
<iframe src="index.php?refresh=5&fn=<?php echo $AR->Current_Output_File ?>" width=800px height=600px></iframe>
