<?php



/**
* Virgin Money Giving API
* By Jon Busby
*/
class VirginMoneyGiving
{
	
	private $pageUrl;
	private $page;
	private $debug = true;
	private $total = 0;
	
	function __construct($pageUrl)
	{
		// set the page up
		$this->pageUrl = $pageUrl;
	}
	
	private function debugLog($msg) {
		if ($this->debug == true) {
			echo $msg."\r\n";
		}
	}
	
	private function getPage() {
		$this->debugLog('getting page at url:'.$this->pageUrl);
		// create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $this->pageUrl);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
		
        // $output contains the output string
        $output = curl_exec($ch);
		if ($output === false) {
			echo "CURL ERROR:".curl_error($ch);
		}
		
        // close curl resource to free up system resources
        curl_close($ch);

		$this->page = $output;
	}
	
	public function getTotal($float = true) {
		// get the page
		$this->getPage();
		// ok get the total out the page
		// to do this - we need to search the page for the right total part
		// i dont understand how the non-greedy part works
		$pattern = '#<div\b[^>]*class="total"[^>]*>([^<]*)</div>#s';

		preg_match($pattern, $this->page, $matches);
		$total = trim($matches[1]);
		$this->total = $total;
		if ($float == true) {
			$newTotal = str_replace('£', '', $total);
			return (float)$newTotal;
		} else {
			return $total;
		}
		
	}
	
	
}


?>