<?php
/***************************************************************************
Name: phpRansack
Description: Php Script to interface with AgentRansack.
id:$id$


****************************************************************************/
class phpAgentRansack
{
    // property declarations
    public $AgentRansack_Path = '';			//Where the AgentRansack.exe file lives.
    public $OutputDirectory = '';			//Where output file should be written to.
	public $option_SearchSubFolders = true;//Should the search include sub directories.  
	public $SearchDirectory = "c:\\";
	public $SearchString = '';
	public $Current_Output_File = '';
	public $FullCMD = '';
	
	
	public $OutputFile = '';

    // method declaration
    public function execute() {
	
		$Config = $this->createConfigArray();
		$this->execute_config($Config);

    }
    
    public function execute_config($ConfigArray)
    {
     	//$OutputFileName = $this->getUniqueOutputFile();
     	
    	$this->Current_Output_File= $ConfigArray['outputFile'];
    
    	$cmd =  "  \"$ConfigArray[AgentRansack_exe]\" -o \"$ConfigArray[outputFile]\" -d \"$ConfigArray[rootSearchDir]\" -f \"$ConfigArray[searchString]\" ";
    	if ($ConfigArray['options']['SearchSubDirs'] )
    	{
    		$cmd .= ' /s ';
    	}
    	$this->FullCMD = $cmd;
    
    	//Wrap entire command in " as per http://www.php.net/manual/en/function.exec.php#101579
    	exec( '"' . $cmd . '"');   
    }
    
    private function getUniqueOutputFile()
    {
    	return 'AgentRansack_Output_' . date('isz') . '.txt';
    }
    
    private function createConfigArray()
    {
    	//Create safe/string versions of options.
    	$Config = $this->EmptyConfig();
    	
    	$Config['AgentRansack_exe'] = $this->AgentRansack_Path;
  		$Config['rootSearchDir'] = $this->SearchDirectory;
  		$Config['searchString'] = $this->SearchString;
  		$Config['outputFile'] =  $this->OutputDirectory  .  $this->getUniqueOutputFile();
  		$Config['options'] = array('SearchSubDirs' => $this->option_SearchSubFolders,
  							'Regex' => false
  							);
  	
    	return $Config;
    	
    	
    	
    	
    
    }
    
    private function EmptyConfig()
    {
    
    	return array(
    	
    				'AgentRansack_exe' => '',
    				'rootSearchDir' => 'c:\\',
    				'searchString' => '',
    				'outputFile' => '',
    				'options' => array('SearchSubDirs' => true,
    									'Regex' => false
    									)
    	    	
    					);
    
    }
}



?>