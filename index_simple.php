<html>
<head>
<title>Ransack Search - Simple Search. </title>
</head>
<body>
<?php

   include('agent_ransack.php');

    //Setup Constants
 		$AGENTRANSACK_PATH = 'C:\Program Files (x86)\Mythicsoft\Agent Ransack\AgentRansack.exe';
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