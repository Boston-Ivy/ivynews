<?php
function get_headers_from_curl_response($response) {
    $headers = array();

    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

    foreach (explode("\r\n", $header_text) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else
        {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }

    return $headers;
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}

function arrayToObject($d) {
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return (object) array_map(__FUNCTION__, $d);
        }
        else {
            // Return object
            return $d;
        }
    }



//FUNCTION FOR LOGGING IN TO IG AND GETTING THE NECESSARY EXTRA SECURITY TOKENS FROM RESPONSE HEADER
function login_to_ig($url, $data_string, $data_header) {

	$url = $url . '/session';

 	$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $data_header);
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_STDERR, $fp);

	$curl_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$header = get_headers_from_curl_response($curl_response);
	$body = objectToArray(json_decode(substr($curl_response, $header_size)));

	// print_r(apache_request_headers());

	if ($curl_response === false) {
	    $info = curl_getinfo($curl);
	    curl_close($curl);
	    //die('error occured during curl exec. Additioanl info: ' . print_r($info));
        die();
	}

	curl_close($curl);

	//$decoded = json_decode($curl_response);

	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
	   die();
        //die('error occured: ' . $decoded->response->errormessage);
	}
	// echo 'response OK! Status code: ' . $status . '<br />';
	// echo "============================<br />";

	// echo "<pre />";
	// echo($curl_response);

	// echo "<br />============================<br />";

	//$headers = get_headers_from_curl_response($curl_response);

	// print_r($headers);

	$cst = $header['CST'];
	$x_security_token = $header['X-SECURITY-TOKEN'];
	$lightstreamer_endpoint = $body['lightstreamerEndpoint'];
	$account_id = $body['accounts'][0]['accountId'];

	$additional_data = array (
		$cst,
		$x_security_token,
		$lightstreamer_endpoint,
		$account_id
		);

	return($additional_data);
}


//FUNCTION TO LIST WATCHLISTS (PASSING THE WATCHLIST ID AS AN OPTIONAL ARGUMENT TO THE FUNCTION)
function list_watchlists($url, $data_header, $watchlist_id = null) {

	$url = $url . '/watchlists/' . $watchlist_id;

	$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $data_header);
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_STDERR, $fp);

	$curl_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	// print_r(apache_request_headers());

	if ($curl_response === false) {
	    $info = curl_getinfo($curl);
	    curl_close($curl);
        die();
	   // die('error occured during curl exec. Additioanl info: ' . print_r($info));
	}

	curl_close($curl);

	$decoded = json_decode($curl_response);

	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
	    die();
        //die('error occured: ' . $decoded->response->errormessage);
	}

	$decoded = objectToArray($decoded);

	//print_r($decoded);
}


function setup_login_data_ig() {
	
    $ig_url = get_option('ig_endpoint_url');	
    $ig_api_key = get_option('ig_api_key');
    $ig_account_identifier = get_option('ig_account_id');
    $ig_account_password = get_option('ig_account_password');
    $ig_watchlist_id = get_option('ig_watchlist_id');

// -------- MODIFY THE INFORMATION BELOW WITH YOUR LOGIN CREDENTIALS --------------
/*
    $url = 'https://demo-api.ig.com/gateway/deal';
    $api_key = '97c38de9fcd765defa98107513f4a9df228c6ae4';
    $identifier = 'DEMO-MOOVEAGENCY2-LIVE';
    $password = 'clientpwd1';

    $data_header = array (
    	'X-IG-API-KEY:' . $api_key,
    	'Content-Type:application/json;charset=UTF-8',
    	'Accept:application/json;charset=UTF-8'
    );
    $data_body = array (
    	'identifier' => $identifier,
    	'password' => $password
    );
    $data = json_encode($data_body);

    $watchlist_id = 3461484; //this is optional. If you don't set it, the function will list all the available watchlists
*/
    $url = $ig_url;
    $api_key =  $ig_api_key;
    $identifier = $ig_account_identifier;
    $password = $ig_account_password;

    $data_header = array (
        'X-IG-API-KEY:' . $api_key,
        'Content-Type:application/json;charset=UTF-8',
        'Accept:application/json;charset=UTF-8'
    );
    $data_body = array (
        'identifier' => $identifier,
        'password' => $password
    );
    $data = json_encode($data_body);

    $watchlist_id = $ig_watchlist_id;

    // CALL THE FUNCTIONS: LOGIN -> GET WATCHLISTS (or a specific watchlist if it is set in your account and you pass it's ID to the function by defining the value og $watchlist_id variable (with watchlist id))

    $response_data = login_to_ig($url, $data, $data_header);


    $cst = $response_data[0];
    $account_token = $response_data[1];
    $ls_endpoint = $response_data[2];
    $account_id = $response_data[3];

    $additional_security_data = array (
    	'CST:' . $cst,
    	'X-SECURITY-TOKEN:' . $account_token
    );

    if (isset($response_data)) {
        //print_r($response_data);
    	// $new_header = array_merge($data_header, $additional_security_data);
    	// list_watchlists($url, $new_header, $watchlist_id);
    	$connection_data = array (
    		'urlRoot' => $url,
    		'apiKey' => $api_key,
    		'account_token' => $account_token,
    		'client_token' => $cst,
    		'watchlist_id' => $watchlist_id,
    		'accountId' => $account_id,
    		'lsEndpoint' => $ls_endpoint
    	);
			
        ?>
        <script>

        var connectionData = <?php echo json_encode($connection_data); ?>;
		
        </script>
        <?php
    }
}
//add_action('wp_footer', 'setup_login_data_ig');

?>