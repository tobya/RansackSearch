<?php
/***************************************************************************
Name: phpRansack
Description: Php Script to interface with AgentRansack.


****************************************************************************/

@include('ARConfig.php');

class phpAgentRansack
{
    // property declarations
	public $AgentRansack_Path = '';			//Where the AgentRansack.exe file lives.
	public $OutputDirectory = 'c:\\temp\\';			//Where output file should be written to.
	public $SearchSubFolders = true;	//Should the search include sub directories.
	public $SearchDirectory = "";  //Root Directory to search - MUST BE SET BY USER.
	public $SearchString = '';
	public $Current_Output_File = '';
	public $FullCMD = '';
	public $ResultArray = array();
	public $OutputFile = '';
	public $WrapInQuotes = true;
  public $Output_Raw = '';
  public $Output_Array = array();
  public $Errors = array();

    function __construct($AR_Path = false)
    {
     $version = phpversion();

     list($maj, $min, $point) = explode('.',$version);
     //5.3 and above
     if ($maj > 4)
     {
        if ($min > 2)
        {
          $this->WrapInQuotes = false;
        }
     }
     
     if ($AR_Path != false)
     {
      $this->AgentRansack_Path = $AR_Path;
     }
     
    
    
    }

    // method declaration
    public function execute() {

		$Config = $this->createConfigArray();
		return $this->execute_config($Config);

    }

    public function execute_config($ConfigArray)
    {
     	//$OutputFileName = $this->getUniqueOutputFile();

    	$this->Current_Output_File= $ConfigArray['outputFile'];
    	
    	if ($ConfigArray['rootSearchDir'] == '')
    	{
    	  $this->Errors[] = 'SearchDirectory must by set by User';
    	  return false;
    	}

    	$cmd =  "  \"$ConfigArray[AgentRansack_exe]\" -o \"$ConfigArray[outputFile]\" -d \"$ConfigArray[rootSearchDir]\" -f \"$ConfigArray[searchString]\" ";
    	if ($ConfigArray['options']['SearchSubDirs'] )
    	{
    		$cmd .= ' /s ';
    	}
    	$this->FullCMD = $cmd;
      echo $this->FullCMD;
    	//Wrap entire command in " as per http://www.php.net/manual/en/function.exec.php#101579
    	if ($this->WrapInQuotes)
    	{
    	exec( '"' . $cmd . '"');
    	}
    	else
    	{
    	exec($cmd);
    	}
    	
    	return $this->getResults();
    	
    }

    public function getUniqueOutputFile()
    {
    	//return 'AgentRansack_Output_' . date('isz') . '.txt';
    	return 'AgentRansack_output.txt';
    }

    private function createConfigArray()
    {
    	//Create safe/string versions of options.
    	$Config = $this->EmptyConfig();

    	$Config['AgentRansack_exe'] = $this->AgentRansack_Path;
  		$Config['rootSearchDir'] = $this->RemoveLastSlash($this->SearchDirectory);
  		$Config['searchString'] = $this->SearchString;
  		$Config['outputFile'] =  $this->OutputDirectory  .  $this->getUniqueOutputFile();
  		$Config['options'] = array('SearchSubDirs' => $this->SearchSubFolders,
  							'Regex' => false
  							);
  		//print_r($Config);
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

    public function getResults($filename = false)
    {

			if (!$filename)
			{
				$fn = $this->Current_Output_File;
			}
			else
			{
				$this->Current_Output_File = $filename;
				$fn = $filename;
			}


    		$this->Output_Raw  = file( "$fn");
    		$this->Output_Array = $this->OutputToArray();
    		return  $this->Output_Array;
    }

    public function ReplacePath($SourcePath, $ReplacementPath)
    {

    	if (count($this->ResultArray) == 0)
    	{
    		getResults($this->Current_Output_File);
    	}

    	foreach($this->ResultArray as $Result)
    	{
    		$Results[] = str_ireplace($SourcePath, $ReplacementPath, $Result);
    	}

    	$this->ResultArray = $Results;
    	return $Results;


    }
    
    private function RemoveLastSlash($Dir)
    {
      if (substr($Dir,-1) == '\\')
      {
        return substr($Dir,0,-1);
      }
    }
    
    private function OutputToArray()
    {
			foreach ($this->Output_Raw as $Line)
			{      
				$LineArray = explode("\t", $Line);
				
				$path_parts = pathinfo($LineArray[0]);
				$Item = array('FilePath' => $path_parts['dirname'] . '\\',
				              'Filename' => $path_parts['basename'],
				              'FileExt' => @$path_parts['extension'],
				              'FileSize' => $LineArray[1],
				              'FileTime' => $LineArray[2]);
				

        $Items[] = $Item;

			}
      return $Items;
    }
}



?>