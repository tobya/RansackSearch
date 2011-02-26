<?php
/***************************************************************************
Name: phpRansack
Description: Php Script to interface with AgentRansack.
id:$id$


****************************************************************************/
class phpAgentRansack
{
    // property declarations
    public $AgentR_Path = '';
    public $root_OutputFile = 'c:\\';
	public $option_SubFolders = '/s';
	public $option_Directory = "c:\\";
	public $option_Search = '';
	public $Current_Output_File = '';
	public $FullCMD = '';
	
	
	public $OutputFile = '';

    // method declaration
    public function execute() {
	
    	$OutputFileName = $this->getUniqueOutputFile();
    	$this->Current_Output_File= $this->root_OutputFile  . $OutputFileName;
    
    	$cmd =  "  \"$this->AgentR_Path\" -o \"$this->root_OutputFile$OutputFileName\" -d \"$this->option_Directory\" -f \"$this->option_Search\" ";
    	$this->FullCMD = $cmd;
    
    	//Wrap entire command in " as per http://www.php.net/manual/en/function.exec.php#101579
    	$r = exec( '"' . $cmd . '"');

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