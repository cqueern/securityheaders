<?php
/*
                                    
*/

########################################################################
#                                                                       
# LIB_sec.php     Security Routines                                      
#                                                                       
#-----------------------------------------------------------------------
# FUNCTIONS                                                             
#                                                                       
#    detect_givegenericfeedback_notify_log()   detects suspicious input, returns a generic error message, sends me a detailed error message email, logs the event, and ends process               
#                                                                       
#                                                                       
########################################################################



/***********************************************************************
detect_givegenericfeedback_notify_log($string, $delineator, $desired, $type)                
-------------------------------------------------------------            
DESCRIPTION:                                                             
        Returns a potion of the string that is either before or after    
        the delineator. The parse is not case sensitive, but the case of
        the parsed string is not effected.								
INPUT:                                                                    
        $string         Input string to parse                            
***********************************************************************/
function detect_givegenericfeedback_notify_log($string)
    {

// note that whatever file you're using this on must have include("LIB_mail.php");
    



// detects suspicious input, 




//this next line is an attempt to do it using an array instead of one bad input at a time (that is, the smarter way to do it) 
//$bad_things = array("<script>", "%3cscript", "\x3cscript");
//if (in_array($string, $bad_things)) 		{



  if(stristr($string, "<script") == TRUE || stristr($string, "%3C") == TRUE || stristr($string, "javascript") == TRUE || stristr($string, ".js") == TRUE || stristr($string, ";") == TRUE || stristr($string, "|") == TRUE || stristr($string, "localhost") == TRUE || stristr($string, "`") == TRUE || stristr($string, "../") == TRUE) {



  
  
  
//returns a generic error message, 

echo "error! please contact the site admin for more details.";

//sends me a detailed error message email, 

$page_this_happened_on = getenv(REQUEST_URI);
$http_user_agent = getenv(HTTP_USER_AGENT);
$http_referer = getenv(HTTP_REFERER);
$ipaddress = getenv(REMOTE_ADDR);
$user_host = getenv(REMOTE_HOST);
$request_method = getenv(REQUEST_METHOD);
$webbot_email_address         = "securityalert@securityheaders.com";
$notification_email_address   = "cqueern@gmail.com";
    
$subject = "ALERT! Security event detected";
$message = "A security event was triggered by someone or something at ".date("r")."\n";
$message = $message . "User's IP address: ";         
$message = $message . $ipaddress."\n";   
$message = $message . "User's host: ";         
$message = $message . $user_host."\n"; 
$message = $message . "User's browser: ";         
$message = $message . $http_user_agent."\n"; 
$message = $message . "Page this happened on: ";         
$message = $message . $page_this_happened_on."\n"; 
$message = $message . "HTTP referer, if any: ";         
$message = $message . $http_referer."\n"; 
$message = $message . "HTTP request method: ";         
$message = $message . $request_method."\n"; 
$message = $message . "What the user entered: ";         
$message = $message . $string."\n\n"; 

 
$address['from']    = $webbot_email_address;
$address['to']    = $notification_email_address;
formatted_mail($subject, $message, $address, $content_type="text/plain");



// logs the event       


//updating the reporting file about usage
		$fd = fopen('securityreportingfile.txt', 'a-');
		if (!fd) {
			echo "Error! Couldn't open/create the file.";
			die;
				}
		fwrite($fd, $message);
		fclose($fd);




//and ends process 





exit;
    
    

		}
    
    
}

?>