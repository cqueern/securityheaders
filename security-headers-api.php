<?php
header("Content-Type: application/json");
$time_start = microtime(true);
error_reporting(0);

include("LIB_http.php");
include("LIB_parse.php");      
include("LIB_http_codes.php");
include("LIB_sec.php");

//input in incoming url parameters turn into variables
$apiKey = $_GET['apikey'];
$client = $_GET['client'];
$requestingipaddress = getenv(REMOTE_ADDR);


//the API endpoint that users hit should look like this:

//https://example.com/security-headers-api.php?host=HostYouAreTesting.com&apikey=CGSakUjtAZhbZ33v4b5wGDePVjN3NMvC&client=whoever

//...where example.com is your website
//...where security-headers-api.php is the name of this script (whatever you end up calling it)
//...where host is a host whose HTTP headers you wish to test
//...where apikey is a secret key you've provided a user; it must match whatever you have in line 32 below
//...where client is any arbitrary text that the user has decided to use to identify themselves


if( $_GET["host"] && $_GET["client"] && ($apiKey == "CGSakUjtAZhbZ33v4b5wGDePVjN3NMvC"))
 {


if(isset($_SERVER['HTTPS']))
{
    if ($_SERVER["HTTPS"] == "on") 
    {

//begin getting the data

$secure_connection = 1;
$arrayOfHTTPSStuff = ["secureConnection" => $secure_connection];

//put the host you want to test in the variable below.
//$host = "example.com";
$host = $_GET["host"];      
        
$ipaddressOfHost = gethostbyname($host);
$countOfGoodThings = 0;
$countOfBadThings = 0;

//going and fetching the headers
//$actual_http_headers = http_header($host = "http://".$host['domain'], $referer="");
$actual_http_headers = http_header($hostPlusHttp = "http://".$host, $referer="");
$httpStatusCode = $actual_http_headers['STATUS']['http_code'];
$contentType = $actual_http_headers['STATUS']['content_type'];
$redirectCount = $actual_http_headers['STATUS']['redirect_count'];


//if we get a live response...
//if (strpos($actual_http_headers['FILE'],'200') !== false or strpos($actual_http_headers['FILE'],'404') !== false) 
if ($actual_http_headers['STATUS']['http_code'] == "200" or $actual_http_headers['STATUS']['http_code'] == "301" or $actual_http_headers['STATUS']['http_code'] == "302" or $actual_http_headers['STATUS']['http_code'] == "404") 
	{


//echo $actual_http_headers['STATUS']['http_code'];

//CSP present?
	$CSPlongsubstring = "Content-Security-Policy";
	$CSPshortsubstring = "x-webkit-csp";
	$CSPreportonlysubstring = "x-webkit-csp-report-only";

        $CSPlongpos = stristr($actual_http_headers['FILE'], $CSPlongsubstring);
        $CSPshortpos = stristr($actual_http_headers['FILE'], $CSPshortsubstring);
        $CSPreportonlypos = stristr($actual_http_headers['FILE'], $CSPreportonlysubstring);
 
        if($CSPlongpos == true) {
                // string needle  found in haystack
                
                $cspRating = 1;
                $countOfGoodThings++;
              
        }
        
 
        
        elseif($CSPreportonlypos == true) {
                // string needle  found in haystack
                
                $cspRating = 1;
                $countOfGoodThings++;

        }       
        
        
         elseif($CSPshortpos == true) {
                // string needle  found in haystack
                
                $cspRating = 1;
                $countOfGoodThings++;
   
        }       
       
        else {
                // string needle NOT found in haystack
                
                $cspRating = 0;
                $countOfBadThings++;

        }

//nosniff present?
	$NOSNIFFsubstring = "nosniff";

        $pos = stristr($actual_http_headers['FILE'], $NOSNIFFsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $noSniffRating = 0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $noSniffRating = 1;
                $countOfGoodThings++;

        }


//Cross Domain Meta Policy present?
	
//documentation http://www.adobe.com/devnet/flashplayer/articles/fplayer9_security.html#articlecontentAdobe_numberedheader_0

	$permittedCrossDomainPoliciessubstring = "Permitted-Cross-Domain-Policies";

        $pos = stristr($actual_http_headers['FILE'], $permittedCrossDomainPoliciessubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $crossDomainPoliciesRating = 0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $crossDomainPoliciesRating = 1;
                $countOfGoodThings++;

        }

//X-XSS-Protection present?

	$XXSSProtectionOFFsubstring = "X-XSS-Protection: 0";
	$XXSSProtectionWINsubstring = "X-XSS-Protection: 1; mode=block";
	$XXSSProtectionMEHsubstring = "X-XSS-Protection: 1";

//documentation http://blogs.msdn.com/b/ieinternals/archive/2011/01/31/controlling-the-internet-explorer-xss-filter-with-the-x-xss-protection-http-header.aspx
//http://blogs.msdn.com/b/ieinternals/archive/2011/01/31/controlling-the-internet-explorer-xss-filter-with-the-x-xss-protection-http-header.aspx

        $XXSSProtectionOFFpos = stristr($actual_http_headers['FILE'], $XXSSProtectionOFFsubstring);
        $XXSSProtectionMEHpos = stristr($actual_http_headers['FILE'], $XXSSProtectionMEHsubstring);
        $XXSSProtectionWINpos = stristr($actual_http_headers['FILE'], $XXSSProtectionWINsubstring);
 
        if($XXSSProtectionWINpos == true) {
                // string needle found in haystack
                
                $XXSSProtectionRating = 1;
                $countOfGoodThings++;

        }
        
        
        elseif($XXSSProtectionMEHpos == true) {
                // string needle found in haystack
                
                $XXSSProtectionRating = 1;
                $countOfGoodThings++;

        }
        
        
        elseif($XXSSProtectionOFFpos == true) {
                // string needle found in haystack
                
                $XXSSProtectionRating = 0;
                $countOfBadThings++;

        }
        
                
        else {
        
		        // string needle NOT found in haystack
                
                $XXSSProtectionRating = 0;
                $countOfBadThings++;


                        }


//Strict-Transport-Security present?

	$HSTSsubstring = "Strict-Transport-Security";

        $pos = stristr($actual_http_headers['FILE'], $HSTSsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $HSTSrating = 0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $HSTSrating = 1;
                $countOfGoodThings++;

        }

//Promiscuous CORS Support present?
//documentation http://enable-cors.org/index.html

	$CORSsubstring = "Access-Control-Allow-Origin: *";

        $pos = stristr($actual_http_headers['FILE'], $CORSsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $CORSrating=1;
                $countOfGoodThings++;

        }
        else {
                // string needle found in haystack
                
                $CORSrating=0;
                $countOfBadThings++;

        }

//X-Frame-Options present?

	$XFOsubstring = "X-Frame-Options";

        $pos = stristr($actual_http_headers['FILE'], $XFOsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $XFOrating=0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $XFOrating=1;
                $countOfGoodThings++;

        }


//UTF-8 Character Encoding present?
	
	//documentation http://www.w3.org/International/O-HTTP-charset
	//documentation http://www.w3.org/International/articles/definitions-characters/#httpheader
	//documentation http://www.w3.org/International/questions/qa-choosing-encodings

	$utf8substring = "utf-8";

        $pos = stristr($actual_http_headers['FILE'], $utf8substring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $utf8rating=0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $utf8rating=1;
                $countOfGoodThings++;

        }


//Server information present?

	$Serversubstring = "Server: ";

        $pos = stristr($actual_http_headers['FILE'], $Serversubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $serverrating=1;
                $countOfGoodThings++;

        }
        else {
                // string needle found in haystack
                
                $serverrating=0;
                $countOfBadThings++;

        }


//X Powered By present?

	$XPBsubstring = "X-Powered-By";

        $pos = stristr($actual_http_headers['FILE'], $XPBsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
                
                $XPBrating=1;
                $countOfGoodThings++;

        }
        else {
                // string needle found in haystack
                
                $XPBrating=0;
                $countOfBadThings++;

        }


//X-Download-Options present?

	$XDOsubstring = "noopen";

        $pos = stristr($actual_http_headers['FILE'], $XDOsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
               
                $XDOrating=0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $XDOrating=1;
                $countOfGoodThings++;

        }


//Public Key Pinning present?

	$PKPsubstring = "Public-Key-Pins";

        $pos = stristr($actual_http_headers['FILE'], $PKPsubstring);
 
        if($pos === false) {
                // string needle NOT found in haystack
  
                $PKPrating=0;
                $countOfBadThings++;

        }
        else {
                // string needle found in haystack
                
                $PKPrating=1;
                $countOfGoodThings++;

        }


}


//$sumGoodAndBad is your total score
$sumGoodAndBad = $countOfGoodThings + $countOfBadThings;
$percentageGood = ($countOfGoodThings / $sumGoodAndBad) * 100;
$time_end = microtime(true);
$time = $time_end - $time_start;


$roundedPercentageGood = round($percentageGood);

$currentTime = date("Y/m/d : H:i:s", time());

$time = round($time, 2);

$headersDetails = $actual_http_headers['FILE'];


//echo "host = ".$host."<br><br>";
//echo "ipaddressOfHost = ".$ipaddressOfHost."<br><br>";
//echo "httpStatusCode = ".$httpStatusCode."<br><br>";
//echo "actual_http_headers['FILE'] = ".$actual_http_headers['FILE']."<br><br>";
$arrayOfWhoStuff = ["host" => $host, "ipaddressOfHost" => $ipaddressOfHost, "http_status_code" => $httpStatusCode, "redirect_count" => $redirectCount, "content_type" => $contentType, "actual_http_headers['FILE']" => $actual_http_headers['FILE']];

//echo "CSPlongsubstring = ".$CSPlongsubstring."<br><br>";
//echo "CSPshortsubstring = ".$CSPshortsubstring."<br><br>";
//echo "CSPreportonlysubstring = ".$CSPreportonlysubstring."<br><br>";
//echo "CSPRating = ".$cspRating."<br><br>";
$arrayOfCSPStuff = ["CSPlongsubstring" => $CSPlongsubstring, "CSPshortsubstring" => $CSPshortsubstring, "CSPreportonlysubstring" => $CSPreportonlysubstring, "CSPRating" => $cspRating];

//echo "NOSNIFFsubstring = ".$NOSNIFFsubstring."<br><br>";
//echo "noSniffRating = ".$noSniffRating."<br><br>";
$arrayOfNOSNIFFStuff = ["NOSNIFFsubstring" => $NOSNIFFsubstring, "noSniffRating" => $noSniffRating];

//echo "permittedCrossDomainPoliciessubstring = ".$permittedCrossDomainPoliciessubstring."<br><br>";
//echo "crossDomainPoliciesRating = ".$crossDomainPoliciesRating."<br><br>";
$arrayOfcrossDomainPoliciesStuff = ["permittedCrossDomainPoliciessubstring" => $permittedCrossDomainPoliciessubstring, "crossDomainPoliciesRating" => $crossDomainPoliciesRating];

//echo "XXSSProtectionOFFsubstring = ".$XXSSProtectionOFFsubstring."<br><br>";
//echo "XXSSProtectionWINsubstring = ".$XXSSProtectionWINsubstring."<br><br>";
//echo "XXSSProtectionMEHsubstring = ".$XXSSProtectionMEHsubstring."<br><br>";
//echo "XXSSProtectionRating = ".$XXSSProtectionRating."<br><br>";
$arrayOfXXSSProtectionStuff = ["XXSSProtectionOFFsubstring" => $XXSSProtectionOFFsubstring, "XXSSProtectionWINsubstring" => $XXSSProtectionWINsubstring, "XXSSProtectionMEHsubstring" => $XXSSProtectionMEHsubstring, "XXSSProtectionRating" => $XXSSProtectionRating];

//echo "HSTSsubstring = ".$HSTSsubstring."<br><br>";
//echo "HSTSrating = ".$HSTSrating."<br><br>";
$arrayOfHSTSStuff = ["HSTSsubstring" => $HSTSsubstring, "HSTSrating" => $HSTSrating];

//echo "CORSsubstring = ".$CORSsubstring."<br><br>";
//echo "CORSrating = ".$CORSrating."<br><br>";
$arrayOfCORSStuff = ["CORSsubstring" => $CORSsubstring, "CORSrating" => $CORSrating];

//echo "XFOsubstring = ".$XFOsubstring."<br><br>";
//echo "XFOrating = ".$XFOrating."<br><br>";
$arrayOfXFOStuff = ["XFOsubstring" => $XFOsubstring, "XFOrating" => $XFOrating];

//echo "utf8substring = ".$utf8substring."<br><br>";
//echo "utf8rating = ".$utf8rating."<br><br>";
$arrayOfutf8Stuff = ["utf8substring" => $utf8substring, "utf8rating" => $utf8rating];

//echo "Serversubstring = ".$Serversubstring."<br><br>";
//echo "serverrating = ".$serverrating."<br><br>";
$arrayOfServerStuff = ["Serversubstring" => $Serversubstring, "serverrating" => $serverrating];

//echo "XPBsubstring = ".$XPBsubstring."<br><br>";
//echo "XPBrating = ".$XPBrating."<br><br>";
$arrayOfXPBStuff = ["XPBsubstring" => $XPBsubstring, "XPBrating" => $XPBrating];

//echo "XDOsubstring = ".$XDOsubstring."<br><br>";
//echo "XDOrating = ".$XDOrating."<br><br>";
$arrayOfXDOStuff = ["XDOsubstring" => $XDOsubstring, "XDOrating" => $XDOrating];

//echo "PKPsubstring = ".$PKPsubstring."<br><br>";
//echo "PKPrating = ".$PKPrating."<br><br>";
$arrayOfPKPStuff = ["PKPsubstring" => $PKPsubstring, "PKPrating" => $PKPrating];

//echo "countOfGoodThings = ".$countOfGoodThings."<br><br>";
//echo "countOfBadThings = ".$countOfBadThings."<br><br>";
//echo "sumGoodAndBad = ".$sumGoodAndBad."<br><br>";
//echo "percentageGood = ".$percentageGood."<br><br>";
//echo "roundedPercentageGood = ".$roundedPercentageGood."<br><br>";
$arrayOfScoreStuff = ["countOfGoodThings" => $countOfGoodThings, "countOfBadThings" => $countOfBadThings, "sumGoodAndBad" => $sumGoodAndBad, "percentageGood" => $percentageGood, "roundedPercentageGood" => $roundedPercentageGood];

//echo "time = ".$time."<br><br>";
//echo "currentTime = ".$currentTime."<br><br>";
$arrayOfTimeStuff = ["time" => $time, "currentTime" => $currentTime];

//here we begin trying to output into an array to later be output into JSON


$arrayForJson = array();
array_push($arrayForJson,$arrayOfHTTPSStuff,$arrayOfWhoStuff,$arrayOfCSPStuff, $arrayOfNOSNIFFStuff, $arrayOfcrossDomainPoliciesStuff, $arrayOfXXSSProtectionStuff, $arrayOfHSTSStuff, $arrayOfCORSStuff, $arrayOfXFOStuff, $arrayOfutf8Stuff, $arrayOfServerStuff, $arrayOfXPBStuff, $arrayOfXDOStuff, $arrayOfPKPStuff, $arrayOfScoreStuff, $arrayOfTimeStuff);
//echo "<pre>";
//print_r($arrayForJson);
//echo "</pre>";



//here's the JSON

echo json_encode($arrayForJson);
die;

     // the following two curly braces close out the test for the $secure_connection at the very beginning of the script after the LIBs are included
    }
}
//this curly brace (i think corresponds with the  if( $_GET["host"] statement
}






//we need to say what happens if we get a bad status code. that if statement should begin here
else 
{
$arrayOfErrorMessage = ["message" => "error. remember your request needs an api key, a client parameter, and a host that resolves with an http status code of 200, 301, 302, or 404 when it receives an HTTP HEAD request, oh, and you have to call this script with https"];
$arrayForJson = array();
array_push($arrayForJson,$arrayOfErrorMessage);
echo json_encode($arrayForJson);
die;

}







?>