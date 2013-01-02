<?php
/*************************************************************

Copyright � 2012 Toby Allen (http://github.com/tobya)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the �Software�), to deal in the Software without restriction, 
including without limitation the rights to use, copy, modify, merge, publish, distribute, sub-license, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
subject to the following conditions:

The above copyright notice, and every other copyright notice found in this software, and all the attributions in every file, and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED �AS IS�, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
****************************************************************/
?>
<html>
	<head>
		<title>Agent Ransack Search</title>
	</head>
	<body>
		<?php
		
    /*******************
    Setup: Change the path to AgentRansack and Logfilepath.
    *******************/
		$AGENTRANSACK_PATH = 'C:/Program Files/Mythicsoft/Agent Ransack/AgentRansack.exe';
		$LOGFILEPATH = 'C:\\';
		
		
		$list_html = '';
    include('..\src\agent_ransack.php');
    $AR = new phpAgentRansack();
    
		if (isset($_GET['Submit']))
		{

    		$AR->AgentRansack_Path = $AGENTRANSACK_PATH;
    		$AR->SearchDirectory = $_GET['rootsearch'];
    		$AR->SearchString = $_GET['search'];
    		$AR->OutputDirectory = $_GET['rootoutput'];


    		if (isset( $_GET['subdir']))
    		{
    			$AR->option_SearchSubFolders = true;
    		}
    		else
    		{
    			$AR->option_SearchSubFolders = false;
    		}
    	
    		$AR->execute();
    		//Uncomment to see generated command string.
    		echo $AR->FullCMD;
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



     $outputResults = $AR->getResults($fn);
     $arraycount = count($outputResults);
     $list_html = "<h3>Results - $arraycount files</h3>  		              
    		              <table cellpadding=2 border=1>";

		 foreach ($outputResults as $Line)
		 {
		    //echo '-----------------------<P>';
        //print_R($L);
        //$Line = $L;
				//$Line = explode("\t", $L);
      //  echo 'line:' ;  print_R($Line);
        //$path_parts = pathinfo($Line[0]);
		    //echo '<P>-----------------------<P>';        
// [FilePath] => D:\development\CIFOnline\depot\websites\online\cookingisfun.ie\live\ [Filename] => index2.php [FileExt] => php [FileSize] => 9 KB [FileTime] => 05/10/2011 16:23:02 )
  			$list_html .= "	<TR><TD>$Line[FilePath]$Line[Filename]</TD><TD>$Line[FileTime]</TD><TD>-</TD></tr>	\n";


			}
			$list_html .= "</table>	";

		}
		
?>
		<h2>Ransack Search </h2>
		<h5>Using the Amazing Agent Ransack</h5>
		<form name="form1" method="get" action="index.php">
			<table width="90%"  border="1" cellspacing="2" cellpadding="2">
				<tr>
					<th width="27%" scope="col"><strong>Search For Files like (* ok) </strong></th>
					<th width="38%" scope="col">
						<div align="left">
							<input name="search" type="text" id="search" size="50" value="<?php echo $_GET['search']; ?>">
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
