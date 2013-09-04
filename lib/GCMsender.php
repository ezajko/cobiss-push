<?php
/**
 * Communicates to the GCM message server. Holds methods to send notifications.
 * @author matjaz
 *
 */
class GCMsender {
 
    //put your code here
    // constructor
    function __construct() {
    }
 
    /**
     * Sends a message to a list of tokens
     * @param unknown $registatoin_ids The list of tokens.
     * @param unknown $title The message title.
     * @param unknown $message The message body.
     * @param unknown $acr The library acronym.
     * @param unknown $memid The membership id.
     * @return the server response.
     */
    public function send_notification($registatoin_ids, $title, $message, $acr, $memid) {
    	return $this->send_notification_mid($registatoin_ids, $title, $message, $acr, $memid,-1);
    }
    /**
     * Sends a message to a list of tokens
     * @param $registatoin_ids The list of tokens.
     * @param $title The message title.
     * @param $message The message body.
     * @param $acr The library acronym.
     * @param $memid The membership id.
     * @param $mid The message ID - needed for deleting and marking the message
     * from the device.
     * @return the server response.
     */
    public function send_notification_mid($registatoin_ids, $title, $message, $acr, $memid,$mid) {
        // include config
        include_once '../lib/config.php';
 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
		
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => array( 	"mid" => $mid,
            					"title" => $title,
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
        return $result;
		
    }
 
}
 
?>