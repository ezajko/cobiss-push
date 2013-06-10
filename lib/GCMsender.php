<?php
 
class GCMsender {
 
    //put your code here
    // constructor
    function __construct() {
 
    }
 
    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $title, $message, $acr, $memid) {
        // include config
        include_once '../lib/config.php';
 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
		
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => array( 	"title" => $title,
								"message" => $message,
								"acr" => strtoupper($acr),
								"memid" => $memid),
        );
		//dump($fields);
		//print_r $fields;
 		//print "\n".GOOGLE_API_KEY."\n";
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
		
		// Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        echo $result;
		
    }
 
}
 
?>