<?php
/*******************************************************************************
* Software: ERROR LOGGER                                                       *
* Version:  1.10                                                               *
* Date:     2010-06-12                                                         *
* Author:   MOHAMMADJAFAR MASHHADI EBRAHIM   --- iran, tehran                  *
* License:  Public                                                             *
*                                                                              *
* ---------                                                                    *
*******************************************************************************/

class ErrorLogger extends Plugin_Core {

	// internal variables
	
	//* ##@
	// how to log errors
	// 0  LOG NONE
	// 1  ECHO          -> echo errormessage immediately on browser
	// 2  Trigger Error -> echo errormessage immediately like internal PHP errors
	// 3  HIDDEN ECHO   -> echo errormessage immediately but you can only see them in html source code
	// 4  LOG TO FILE   -> write errormessages into a log file. $__filename contains log file name and address
	//
	// @ var type: integer number from 0 to 4
	private $__logtype;
	
	
	//* ##@
	// LOGS FILE NAME
	// @ (only if $__logtype == 4)
	//
	// @ var type: string
	private $__filename;
	
	
	//* ##@
	// Trigger Error Type
	// 0  NOTICE
	// 1  WARNING
	// 2  ERROR
	//
	// @ var type: integernumber from 0 to 2
	private $__Terrortype;
	
	
	//* ##@
	// An array that contain error messages
	// You can access it by some public functions
	//
	// @ var type: string array
	private $__messages = array();
	
	
	//* ##@
	//  File handler
	//  filehandle = fopen(...);
	//  fclose(filehandler);
	//  ...
	//
	// @ var type: filepointer
	private $__filehdl;
	
	//* ##@
	//  File open type:
	//  Append
	//  Rewrite
	//
	// @ var type: string 
	// @---- CAUTION: CASE SENSITIVE
	private $__fileopentype;
	
	//* ##@
	//  printf friendly pattern to write error
	// a good example :
	// "<strong>ERROR:</strong> " . date("d F Y h:i a") . " : %s"
	//
	// @ var type: string 
	private $__errorpattern;
	
	//* ##@
	// internal error constants
	// there is no function 4 U to set it.
	// it will be set automatically by constructor function
	//
	// @ var type: string array
	private $intererror = array();
	
	//************************************************************************
	
	public function __construct(){
		$this->__filename = WPHT_PLUGINDIR.'/_errorlog.txt';
		$this->__fileopentype = 'Append';
		$this->__logtype = 4;
		$this->__errorpattern = "ERROR:" . date("d F Y h:i a") . " : %s";
		$this->ErrorLoggerInit();
	}
	
	// ##@ set necessary variables
	// ##@ call it before ErrorLoggerInit then Enjoy
	public function __set($index,$value)
	{
		if($this->__CHECK($index,$value))
			$this->__SETINTERVAR($index,$value);
	}
	
	// ##@ quasi-constructor function
	public function ErrorLoggerInit ()
	{
		$this->intererror = array(
		"NOTWRITABLE"        => "The selected logfile isn't writable",
		"LOGTYPENOTDEFIENED" => "The entered logtype is not recognized",
		"TETYPENOTDEFINED"   => "The entered Trigger Error type is not recognized",
		"FOTYPENOTDEFINED"   => "The entered File open mode is not recognized",
		"NOFILESETTING"      => "Please set log file name and open type!",
		"FILEWRITINGERROR"   => "An Error occured during writing to err file"
		);
		
		if ($this->__logtype == 4)
		{
			# class should log errors into a file
			$this->__initfile();
		}
	}
	
	// ##@ Destructor
	public function __destruct()
	{
		if ($this->__logtype == 4)
		{
			# write log into a particular file and close it
			@fclose($this->__filehdl);
		}
	}
	
	// ##@ add an error message
	public function add_message($message)
	{		
		/* Add call stack to the error log */
		//$message .= "\n".print_r(debug_backtrace(), TRUE);
		//$message .= "\n";
		// Remove call stack from error log on 01112016
		$this->__messages[] = "\n".PHP_EOL.$message.PHP_EOL;    // add the error message to array
		if($this->__echomessage($message)) // handle logging { echo , invisible echo , trigger error , file}
			return true;
		else
			$this->__internalerror("FILEWRITINGERROR"); return false;	
	}
	
	//************************************************************************
	
	// ##@ echo error
	private function __echomessage($message){
		switch ($this->__logtype)
		{
			case 0: # LOG NONE
			break;
			case 1: # ECHO
			 printf($this->__errorpattern."<br /><br />",$message);
			 break;
			case 2: # TRIGGER ERROR
			 trigger_error(sprintf($this->__errorpattern,$message), $this->__TErrorNumber2Const($this->__Terrortype));
			 break; 
			case 3: # HIDDEN ECHO
			 printf("<!--" .$this->__errorpattern. "-->",$message);
			 break;
			case 4: # LOG INTO A FILE
			 return $this->__writetofile($message);
			 break;  
			default:
			 return false;
		}
		return true;
	}
	
	// ##@ write message to file
	private function __writetofile($message)
	{		
		try {
			error_reporting(0);
			$message = sprintf($this->__errorpattern,$message);
			if($this->__filehdl)
			{
				# is it a writable file
				if(is_writable($this->__filename))
				{
					fwrite($this->__filehdl,($message));
					fclose($this->__filehdl);
					error_reporting(E_ALL);
					return true;
				}
				else
				{
					$this->__internalerror("NOTWRITABLE"); # class cannot write into file
				}
			}else
			{
				error_reporting(E_ALL);
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	// ##@ check whether $value of $index is acceptable or not
	private function __CHECK($index,$value)
	{
		$return = false; # default is false,if the value pass checks it will become true
		
		switch ($index)
		{
			case "logtype":    # check $value for a lotype
				# is $value an integer between 0 and 4 ?
				if($value == 0 or $value == 1 or $value == 2 or $value == 3 or $value == 4) 
					$return = true; # it is an acceptable value!
				else
					$this->__internalerror("LOGTYPENOTDEFIENED");
			break;
			
			case "fotype":
				#is $value == "Append" or "Rewrite"
				if($value == "Append" or $value == "Rewrite") 
					$return = true; # it is an acceptable value!	
				else
					$this->__internalerror("FOTYPENOTDEFINED");		
				break;
			
			case "TEtype":
			if(is_nan($value)) # is $value a number?
			{
				# is $value an integer between 0 and 2 ?
				if($value == 0 or $value == 1 or $value == 2) 
					$return = true; # it is an acceptable value!
				else
					$this->__internalerror("TETYPENOTDEFINED");
			}
			break;
			
			case "lfname":
			case "errorpattern":
				$return = true;
			break;
			
			default:
				
				$return = false; # if $index was not acceptable !!
		}
		
		###
		return $return; 
	}
	
	// ##@ a private function that assigns internal variables' values
	private function __SETINTERVAR($index,$value)
	{
		switch ($index)
		{
			case "logtype":
				$this->__logtype = $value; break;
			case "lfname":
				$this->__filename = $value; break;
			case "fotype":
				$this->__fileopentype = $value; break;
			case "errorpattern":
				$this->__errorpattern = $value; break;
			case "TEtype":
				$this->__Terrortype = $value; break;
			default:
				return false;
		}
		
		return true;
	}
	
	// ##@ a private function that converts this class arbitrary numbers for TERROR ERROR TYPE to php arbitrary constants
	private function __TErrorNumber2Const($value)
	{
		switch($value)
		{
			case 0:
				return E_USER_NOTICE;
			break;
			case 1:
				return E_USER_WARNING;
			break;
			case 2:
				return E_USER_ERROR;
			break;
			default:
				$this->__intererror("TETYPENOTDEFINED");
		}
	}
	
	// ##@ class should log errors into a file so we initialize file writing system
	private function __initfile()
	{
		# check if there is a file name
		if($this->__filename == NULL or $this->__fileopentype == NULL)
		{
			$this->__internalerror("NOFILESETTING");
		}
		
		# delete file and write new entries or append
		if($this->__fileopentype = "Append")
		{
			$this->__filehdl = fopen($this->__filename,"a"); # open file, append mode
		}
		elseif($this->__fileopentype = "Rewrite")
		{
			$this->__filehdl = fopen($this->__filename,"w"); # open file, rewrite mode
		}
	}
	
	// ##@ show an error that occured in the class
	private function __internalerror($id)
	{
		die("<br />".$this->intererror[$id]."<br />");
	}
	
}
?>