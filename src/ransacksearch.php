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
/***************************************************************************
Name: RansackSearch
Description: Php Script to interface with AgentRansack.
Source: http://github.com/tobya/RansackSearch
****************************************************************************/


class RansackSearch
{
    // property declarations
	public $AgentRansack_Path = '';			        //Where the AgentRansack.exe file lives.
	public $OutputDirectory = '';			//Where output file should be written to.
	public $SearchSubFolders = true;	          //Should the search include sub directories.
	public $SearchDirectory = "";               //Root Directory to search - MUST BE SET BY USER.
	public $SearchString = '';                  //File Match String
  public $SearchContentString = '';           //Content match string.
	public $Current_Output_File = '';
	public $FullCMD = '';
	public $ResultArray = array();
	public $OutputFile = '';
	public $WrapInQuotes = true;
  public $Output_Raw = '';
  public $Output_Array = array();
  public $Errors = array();
  public $SessionID = '';

    function __construct($AR_Path = false)
    {

     $this->OutputDirectory = dirname(__FILE__) . '\\';  //use script dir for output.
     $version = phpversion();

     list($maj, $min, $point) = explode('.',$version);
     //5.3 and above
     if ($maj > 4)
     {
        if ($min > 2)
        {
          //There is  a difference how later versions of php >5.3 handle output calls to
          //exec. Essential to Wrap full cmd in quotes.
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
       echo 'in execute';
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

    	$cmd =  "  \"$ConfigArray[AgentRansack_exe]\" -o \"$ConfigArray[outputFile]\" -d \"$ConfigArray[rootSearchDir]\" -f \"$ConfigArray[searchString]\" -ofb  ";
      $cmd .=  " -c \"$ConfigArray[SearchContentString]\"  ";
    	if ($ConfigArray['options']['SearchSubDirs'] )
    	{
    		$cmd .= ' -s ';
    	}
    	$this->FullCMD = $cmd;
      echo $this->FullCMD;
     // echo $cmd;
      
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
      $Sess = $this->getSessionID();
    	return "AgentRansack_output_$Sess.txt";
    }

    private function createConfigArray()
    {
    	//Create safe/string versions of options.
    	$Config = $this->EmptyConfig();

    	$Config['AgentRansack_exe'] = $this->AgentRansack_Path;
  		$Config['rootSearchDir'] = $this->RemoveLastSlash($this->SearchDirectory);
  		$Config['searchString'] = $this->SearchString;
      $Config['SearchContentString'] = $this->SearchContentString;
  		$Config['outputFile'] =  $this->OutputDirectory  .  $this->getUniqueOutputFile();
  		$Config['options'] = array( 'SearchSubDirs' => $this->SearchSubFolders,
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
            'SearchContentString' => '',
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
      if (substr($Dir,-1) == DIRECTORY_SEPARATOR)
      {
        return substr($Dir,0,-1);
      }
      else
      {
        return $Dir;
      }
    }


    public function getSessionID()
    {
      if ($this->SessionID > ''){
        return $this->SessionID;
      }
      else{
        session_start();
        $this->SessionID = session_id();
        return $this->SessionID;
      }
    }
    
    private function OutputToArray()
    {
    
      
    
			foreach ($this->Output_Raw as $Line)
			{      
			  //echo $Line;
			  
				$LineArray = explode("\t", $Line);
       // echo $LineArray[0] . "\n<BR>";
        if (!is_numeric(trim($LineArray[0]))){ //if numeric line number so ignore.
          if (trim($LineArray[0]) <> ''){ //Empty Line.
				$path_parts = pathinfo($LineArray[0]);
				$Item = array('FilePath' => $path_parts['dirname'] . '\\',
				              'Filename' => $path_parts['basename'],
				              'FileExt' => @$path_parts['extension'],
				              'FileSize' => $LineArray[1],
				              'FileTime' => $LineArray[2]);
				

        $Items[] = $Item;
        }}
			}
      return $Items;
    }
}



?>