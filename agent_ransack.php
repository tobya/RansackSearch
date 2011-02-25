<?php





class phpAgentRansack
{
    // property declarations
    public $AgentR_Path = '';
  //  public $AgentR_exe = 'AgentRansack.exe';
    public $root_OutputFile = 'c:\\';
	public $option_SubFolders = '/s';
	public $option_Directory = "c:\\";
	public $option_Search = '';
	public $Current_Output_File = '';
	public $FullCMD = '';
	
	
	public $OutputFile = '';

    // method declaration
    public function execute() {
	//$RansackPath = "C:\Program Files\Mythicsoft\Agent Ransack";
	$OutputFileName = $this->getUniqueOutputFile();
	$this->Current_Output_File= $this->root_OutputFile  . $OutputFileName;
	/*$cmd = 	'"' . $this->AgentR_Path 
	.  '" -o "c:\'
	  . $OutputFileName
	  . '" -d "' 
	  . $this->option_Directory 
					. '"' 
					. ' -f "'
					 . $this->option_Search 
					 . '"' 
					 . $this->option_SubFolders;*/
	$cmd =  "  \"$this->AgentR_Path\" -o \"c:\\$OutputFileName\" -d \"$this->option_Directory\" -f \"$this->option_Search\" ";
	//$cmd = 'c:\BCSSERVER\bcsstudents\oneoff\agent\agentransack.bat';
	$this->FullCMD = $cmd;
	echo $cmd;
	//Wrap entire command in " as per http://www.php.net/manual/en/function.exec.php#101579
	$r = exec( '"' . $cmd . '"', $A, $RR);
	echo $r;
	print_R($A);
	echo $RR;

    }
    
    public function execute_config($ConfigArray)
    {
    
    }
    
    private function getUniqueOutputFile()
    {
    	return 'AgentRansack_Output_' . date('isz') . '.txt';
    }
}



?>