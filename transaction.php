<?php
/**
 * Created by PhpStorm.
 * User: Tayyab Ejaz
 * Date: 09/08/2018
 * Time: 2:56 PM
 */

require "cryptopay/vendor/autoload.php";
/**
 * Logging class:
 * - contains lfile, lwrite and lclose public methods
 * - lfile sets path and name of log file
 * - lwrite writes message to the log file (and implicitly opens log file)
 * - lclose closes log file
 * - first call of lwrite method will open log file implicitly
 * - message is written with the following format: [d/M/Y:H:i:s] (script name) message
 */
class Logging {
	// declare log file and file pointer as private properties
	private $log_file, $fp;
	// set log file (path and name)
	public function lfile($path) {
		$this->log_file = $path;
	}
	// write message to the log file
	public function lwrite($message) {
		// if file pointer doesn't exist, then open log file
		if (!is_resource($this->fp)) {
			$this->lopen();
		}
		// define script name
		$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		// define current time and suppress E_WARNING if using the system TZ settings
		// (don't forget to set the INI setting date.timezone)
		$time = @date('[d/M/Y:H:i:s]');
		// write current time, script name and message to the log file
		fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
	}
	// close log file (it's always a good idea to close a file when you're done with it)
	public function lclose() {
		fclose($this->fp);
	}
	// open log file (private method)
	private function lopen() {
		// in case of Windows set default log file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$log_file_default = 'c:/php/logfile.txt';
		}
		// set default log file for Linux and other systems
		else {
			$log_file_default = '/tmp/logfile.txt';
		}
		// define log file from lfile method or use previously set default
		$lfile = $this->log_file ? $this->log_file : $log_file_default;
		// open log file for writing only and place file pointer at the end of the file
		// (if the file does not exist, try to create it)
		$this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
	}
}

function my_redirect() {
	?>
		<meta http-equiv="refresh" content="3; URL=http://security-01st.com/wp-admin/admin.php?page=plugin-posts"> <?php
	}
if (isset($_GET['token'])) {
	$amount = $_GET['token'];
	$test = trans($amount);
	echo $test;
	my_redirect();
}

global $tokens;
$amount = $tokens;
function trans($amount) {


	$params               = array();
	$params['apiKey']     = "70e09a73699f7396fb24";
	$params['apiSecret']  = "9c6c5427d134f0ec2c636388b2208d67ff809d001168883629f4e4c1c902a79e";
	$params['apiBaseUrl'] = 'https://sandboxapi.ost.com/v1.1/';


	$ostObj             = new OSTSdk( $params );
	$transactionService = $ostObj->services->transactions;

	$executeParams                 = array();
	$executeParams['to_user_id']   = '2a39d3e2-50ca-4876-9072-3f3e7189da4a';
	$executeParams['from_user_id'] = 'eb476135-7360-4318-b917-e365adb29290';
	$executeParams['action_id']    = '39725';
	$executeParams['amount']       = $amount;

	$response = $transactionService->execute( $executeParams )->wait();

	/*** Writing Data to File ***/
	$log = new Logging();
	$log->lfile('mylog.txt');
	$log->lwrite('Transaction:'.json_encode($response));
	$log->lclose();

	$response = $response['success'];

	if ( $response == true ) {

		return "Success";

	} else {
		return "Not Completed";
	}
}