<?php
$TokenString='';
$ComputeURL='';


function GetTokens()
{
        // create curl resource 
        $ch = curl_init();
	$data_string = file_get_contents ( 'Post.json' ); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://keystone.cord.lab:5000/v3/auth/tokens"); 

	//POST
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        //return the transfer as a string 
	curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

	//ignore ssl cert
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	//Header
	curl_setopt($ch, CURLOPT_HTTPHEADER, 
	array(	'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);

        // $output contains the output string 
        $output = curl_exec($ch); 
	//get headers Token
	//echo $output;
	
	list($headers, $response) = explode("\r\n\r\n", $output, 2);
	$headers = explode("\n", $headers);
	
	$Tokens='';
	
	foreach($headers as $header) {
		if (stripos($header, 'X-Subject-Token') !== false) {
			//echo $header;
			$GLOBALS['TokenString'] =  explode(" ",$header)[1];
			$Tokens = $header;
		}
	}

        // close curl resource to free up system resources

 
	curl_setopt($ch, CURLOPT_HEADER, false);

	//echo '<br>';
	//get json value
	$output2 = curl_exec($ch);

	$Temp=json_decode($output2);
	$GLOBALS['ComputeURL'] = $Temp->token->catalog[3]->endpoints[1]->url;
	//echo $ComputeURL;


	//echo $output2;
        curl_close($ch);      
	
	//echo $Tokens;
	
	//return $Tokens;
}
//GetTokens();


//echo  GetTokens();
//echo $TokenString . '<br>';
//echo $ComputeURL . '<br>';


function GetServers()
{
	GetTokens();

        // create curl resource 
        $ch2 = curl_init();
	$URL =  $GLOBALS['ComputeURL'];
//	echo "$URL/servers" .  '<br>';
	//echo $GLOBALS['ComputeURL'] . '/servers'; 
        // set url 
        curl_setopt($ch2, CURLOPT_URL,  "$URL/servers" );

        //return the transfer as a string 
        curl_setopt($ch2, CURLOPT_HEADER, false);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

        //ignore ssl cert
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);

        //Header
	//replace special char (0x0a 0x0d)
	$Tokens = preg_replace('~\r\n?~', "", sprintf("X-Auth-Token: %s ",$GLOBALS['TokenString']));
	//byte test
	/*for($i = 0; $i < strlen($Tokens); $i++)
	{
		echo ord($Tokens[$i]). ' ';
	}*/

//	echo '<br>' . $Tokens . '<br>';
	curl_setopt($ch2, CURLOPT_HTTPHEADER, array( $Tokens ));
//array(
//	"X-Auth-Token: $Tokens"));	

        // $output contains the output string 
        $output = curl_exec($ch2);
        //echo $output;

        // close curl resource to free up system resources 
        curl_close($ch2);

        return $output;
}
//GetServers();


?>
