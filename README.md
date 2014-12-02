securityheaders
===============

This script sends a simple HTTP HEAD request to analyze the HTTP headers of any site. You give it a host to analyze, and it outputs the results in JSON.

##Mechanics of the API##
###Request URL###

Send the following URL:

https://example.com/security-headers-api.php?host=HostYouAreTesting.com&apikey=CGSakUjtAZhbZ33v4b5wGDePVjN3NMvC&client=whoever

...where example.com is wherever you decide to host this PHP script.

###Required URL parameters###

The host parameter indicates the host to look up. Examples of valid hosts: 

* example.org
* some.example.org
* 173.194.46.9

The apikey parameter specifies an API key that you provide. The apikey shown above is just a placeholder for now, and is included in the security-headers-api.php script, but you should change that out immediately.

The client parameter indicates the type of client. You can choose any name. However, we suggest you choose a name that represents the true identity of the client, such as “yourawesomecompanyname”.

##Security Headers##

This script searches for the presence or absence of the following:

* Access Control Allow Origin
* Content Security Policy
* Cross Domain Meta Policy
* Noopen
* NoSniff
* Public Key Pinning
* Server Information
* Strict Transport Security
* UTF-8 Character Encoding
* X-Frame-Options
* X-Powered-By
* X-XSS-Protection
