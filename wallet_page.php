<?php
/**
 * Created by PhpStorm.
 * User: Tayyab Ejaz
 * Date: 03/08/2018
 * Time: 5:26 PM
 */

require 'cryptopay/vendor/autoload.php';

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

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

if(isset($_GET['amount']) && isset($_GET['transaction'])){
	$amount = $_GET['amount'];
	transaction($amount);
} elseif(isset($_GET['balance'])){
	//$amount = $_GET['amount'];
	balance();
}elseif(isset($_GET['ledger'])){
	//$amount = $_GET['amount'];
	ledger();
}elseif(isset($_GET['amount']) && isset($_GET['add_transaction'])){
	$amount = $_GET['amount'];
	add_transaction($amount);
}


/*** Check the Balance of User ***/
function balance(){
	$params = array();
	$params['apiKey']="dab6b83d503d92ab5c9e";
	$params['apiSecret']="2122835d0d902a84e3e82616769ede7db4d81fb26d99e83fc670be79bcc3b8f5";
	$params['apiBaseUrl']='https://sandboxapi.ost.com/v1.1/';

	$ostObj = new OSTSdk($params);
	$balanceService = $ostObj->services->balances;

	$getParams = array();
	$getParams['id'] = '3a3a6698-e36e-4690-aa17-35fae06a0c83';
	$response = $balanceService->get($getParams)->wait();

	/*** Writing Data to File ***/
	$log = new Logging();
	$log->lfile('mylog.txt');
	$log->lwrite('Balance:'.json_encode($response));
	$log->lclose();

	$response = $response['data']['balance']['available_balance'];

	return $response;

}

/*** Print The Ledger ***/
function ledger(){
	$params = array();
	$params['apiKey']="70e09a73699f7396fb24";
	$params['apiSecret']="9c6c5427d134f0ec2c636388b2208d67ff809d001168883629f4e4c1c902a79e";
	$params['apiBaseUrl']='https://sandboxapi.ost.com/v1.1/';

	$ostObj = new OSTSdk($params);
	$ledgerService = $ostObj->services->ledger;

	$getParams = array();
	$getParams['id'] = 'eb476135-7360-4318-b917-e365adb29290';
	$response = $ledgerService->get($getParams)->wait();

	/*** Writing Data to File ***/
	$log = new Logging();
	$log->lfile('mylog.txt');
	$log->lwrite('Ledger:'.json_encode($response));
	$log->lclose();

	$response = $response['data']['transactions'];
	return $response;
}

$bal= balance();
//var_dump($bal);
$ledger = ledger();
$id_arr = array();
$amount_arr = array();

foreach ($ledger as $ele)
{
    $id_arr[] = $ele['id'];
    $amount_arr[] = $ele['amount'];
}

//var_dump($ledge);



?>
<style>
	table {
		border-spacing: 5px;
		border-width: thin;
		border-collapse: collapse;
		background: white;
		border-radius: 6px;
		overflow: hidden;
		max-width: 1100px;
		width: 100%;
		margin: 0 auto;
		position: relative;
		align-content: center;
	}
	table * {
		position: relative;
	}
	table td, table th {
		padding-left: 8px;
	}
	table thead tr {
		height: 60px;
		background: #048BA8;
		font-size: 16px;
		align-content: center;
	}
	table tbody tr {
		height: 48px;
		border-bottom: 1px solid #E3F1D5;
	}
	table tbody tr:last-child {
		border: 0;
	}
	table td, table th {
		text-align: center;
	}
	table td.l, table th.l {
		text-align: right;
	}
	table td.c, table th.c {
		text-align: center;
	}
	table td.r, table th.r {
		text-align: center;
	}

	@media screen and (max-width: 35.5em) {
		table {
			display: block;
		}
		table > *, table tr, table td, table th {
			display: block;
		}
		table thead {
			display: none;
		}
		table tbody tr {
			height: auto;
			padding: 8px 0;
		}
		table tbody tr td {
			padding-left: 45%;
			margin-bottom: 12px;
			column-span: 2;
		}
		table tbody tr td:last-child {
			margin-bottom: 0;
		}
		table tbody tr td:before {
			position: absolute;
			font-weight: 700;
			width: 40%;
			left: 10px;
			top: 0;
		}
		table tbody tr td:nth-child(1):before {
			content: "Code";
		}
		table tbody tr td:nth-child(2):before {
			content: "Stock";
		}
		table tbody tr td:nth-child(3):before {
			content: "Cap";
		}
		table tbody tr td:nth-child(4):before {
			content: "Inch";
		}
		table tbody tr td:nth-child(5):before {
			content: "Box Type";
		}
	}
	body {
		background: lightgray;
		font: 400 14px 'Calibri','Arial';
		padding: 20px;
	}

	blockquote {
		color: white;
		text-align: center;
	}
	p
	{
		font-size: x-large;
		font-weight: bold;
		padding: 5px 5px 5px 5px;
		width: 100%;
		height: auto;
	}

	#label_div
	{
		padding: 5px 5px 5px 5px;
		width: 100%;
		height: auto;
		align-content: center;
	}

    .th_style
    {
        background: white;
        color: #048BA8 ;
    }

</style>

<table>
	<thead>
		<th> USD Balance </th>
		<th class="th_style"><?php echo $bal*0.01 ?> </th>
	</thead>
	<thead>
		<th> CPY Balance</th>
		<th class="th_style"> <?php echo $bal ?>  </th>
	</thead>

</table>
<div id="label_div">
	<p> Transaction Details </p>
</div>
	<table>
	<thead>
		<th> Token Amount </th>
		<th> Price in USD </th>
	</thead>

    <?php
        $i=0;
        foreach ($ledger as $ele){

    ?>
	<tr>
		<td> <?php echo $id_arr[$i];  ?></td>
		<td> <?php echo $amount_arr[$i]; ?> </td>
	</tr>
        <?php }
        ?>
</table>
