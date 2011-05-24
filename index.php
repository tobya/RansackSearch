<html>
	<head>
		<title>Agent Ransack Search</title>
	</head>
	<body>
		<?php
		//Edit to your path to Agent Ransack exe.
		$AGENTRANSACK_PATH = 'C:\Program Files (x86)\Mythicsoft\Agent Ransack\AgentRansack.exe';
		$LOGFILEPATH = 'C:\\';
		$list_html = '';
    	include('agent_ransack.php');
    	$AR = new phpAgentRansack();
    //	unlink($AR->Current_Output_File);
		if (isset($_GET['Submit']))
		{


    		$AR->AgentRansack_Path = $AGENTRANSACK_PATH;
    		$AR->SearchDirectory = $_GET['rootsearch'];
    		$AR->SearchString = $_GET['search'];
    		$AR->OutputDirectory = $_GET['rootoutput'];
    		//$AR->WrapInQuotes = true;
    		//Please remove following line once this is done properly.
    	//	unlink($AR->OutputDirectory . $AR->getUniqueOutputFile());
    		if (isset( $_GET['subdir']))
    		{
    			$AR->option_SearchSubFolders = true;
    		}
    		else
    		{
    			$AR->option_SearchSubFolders = false;
    		}
    		echo $AR->FullCMD;
    		$AR->execute();
		}

		if (isset($_GET['view']))
		{
			if (isset($_GET['fn']))
			{
				$fn = $_GET['fn'];
			}
			else
			{
				$fn = $AR->Current_Output_File;
			}



    		$outputfile = $AR->getResults($fn);
    		//$outputfile = $AR->ReplacePath($AR->SearchDirectory, 'BASE');
    		$arraycount = count($outputfile);
    		$list_html = "<h3>Results - $arraycount files</h3>
    		<a href='index.php?view=yes&fn=$fn'>Refresh Results</a><BR>;
    		<table cellpadding=2 border=1>";

			foreach ($outputfile as $L)
			{




				$Line = explode("\t", $L);

$path_parts = pathinfo($Line[0]);

//echo $path_parts['dirname'], "\n";
//echo $path_parts['basename'], "\n";
//echo $path_parts['extension'], "\n";
//echo $path_parts['filename'], "\n";
			//	$list_html .= "	<TR><TD>$Line[0]</TD><TD>$Line[1]</TD><TD>$Line[2]</TD></tr>	\n";
			//

			$list_html .= "	<TR><TD><a href='http://127.0.0.1:10001/core/downloadfile?filepath=". urlencode($Line[0]) . '&filename=' . urlencode($path_parts['filename'])."'>$Line[0]</a></TD><TD>$Line[1]</TD><TD>$Line[2]</TD></tr>	\n";

//http://127.0.0.1:10001/core/downloadfile?filepath=C%3A%5CUsers%5CToby%5CDocuments%5CAptana+Studio+3+Workspace%5C.metadata%5C.plugins%5Ccom.aptana.portablegit.win32%5Clib%5Ctk8.5%5Cttk%5Ctreeview.tcl&filename=treeview.tcl
			}
			$list_html .= "</table>	";

		}
		?>
		<h2>Agent Ransack </h2>
		<form name="form1" method="get" action="index.php">
			<table width="90%"  border="1" cellspacing="2" cellpadding="2">
				<tr>
					<th width="27%" scope="col"><strong>Search For Files like (* ok) </strong></th>
					<th width="38%" scope="col">
						<div align="left">
							<input name="search" type="text" id="search" size="50">
						</div>
					</th>
					<th width="35%" scope="col">&nbsp;</th>
				</tr>
				<tr>
					<td><strong>Begin Search in this directory </strong></td>
					<td><input name="rootsearch" type="text" id="rootsearch" value="d:\development" size="50"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Search Sub Directories </strong></td>
					<td><input name="subdir" type="checkbox" id="subdir" value="yes" size="50" ></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Display Results Below </strong></td>
					<td><input name="checkbox" type="checkbox" value="checkbox" checked></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>Directory to Place Result File </strong></td>
					<td><input name="rootoutput" type="text" id="rootoutput" value="c:\temp\" size="50"></td>
					<td><input type="submit" name="Submit" value="Submit"></td>
				</tr>
				<input type=hidden value=yes name=view>
			</table>
		</form>
		<p>&nbsp;</p>
		<?php echo $list_html; ?>
	</body>
</html>
