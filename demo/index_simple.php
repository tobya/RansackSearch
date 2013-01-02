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
<title>Ransack Search - Simple Search. </title>
</head>
<body>
<?php

   include('..\src\agent_ransack.php');

    //Setup Constants
    $AGENTRANSACK_PATH = 'C:/Program Files/Mythicsoft/Agent Ransack/AgentRansack.exe';
 		$RootSearchPath = 'D:\development\\';

 		
 		$AgentR = new phpAgentRansack($AGENTRANSACK_PATH);
 		
 		$AgentR->SearchDirectory = $RootSearchPath;
 		$AgentR->SearchString =  @$_GET['search'] ?  $_GET['search']:  '.php'  ;
 		$AgentR->SearchSubFolders = true;
 		
    $Results =	$AgentR->Execute();
    if ($Results == false)
    {
      print_r($AgentR->Errors);
      exit;
    }
    echo "You Searched for $AgentR->SearchString <P> <UL>";
    foreach ($Results as $Result)
    {
      echo "<LI>" . $Result['FilePath'] . $Result['Filename'] ; 
    }
    echo "</UL>";
    

?>

</body>
</html>