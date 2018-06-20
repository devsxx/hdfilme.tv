<?php

namespace Anhi\Shared\Services;

use Anhi\Shared\Services\MediaVaultOptions;

use Helper;

class MediaVault
{
	public $videoURL;
	public $secret;
	public $options;
	
	function __construct($videoURL, $secret, $options=NULL){
		$this->videoURL = $videoURL;
		$this->secret = $secret;
		
		if(!isset($options) || !($options instanceof MediaVaultOptions)){
			$this->options = new MediaVaultOptions();
		}else{
			$this->options = $options;
		}
	}
	
	function compute(){
		if(strlen($this->videoURL) == 0 || strlen($this->secret) == 0){
			throw new Exception("VideoURL and Secret are required.");
		}
		
		$result = $this->videoURL;
		$urlParams = "";
		$hash = "";
		
		if(strlen($this->options->referrer) > 0){
			$urlParams = "&ru=".strlen($this->options->referrer);
			$hash = $this->options->referrer;
		}
		if(strlen($this->options->pageURL) > 0){
			$urlParams .= "&pu=".($this->options->pageURL);
			$hash .= $this->options->pageURL;
		}
		
		if(strlen($this->options->ipAddress) > 0) $urlParams .= "&ip=" . $this->options->ipAddress;

		if (!Helper::isMobile())
		{
			if(strlen($this->options->startTime) > 0) $urlParams .= "&s=" . $this->options->startTime;
			if(strlen($this->options->endTime) > 0) $urlParams .= "&e=" . $this->options->endTime;
		}
		
		if(strlen($urlParams) > 0){
			$urlParams = substr($urlParams, 1, strlen($urlParams));
			
			if(strpos($result, "?")){
				$result .= "&".$urlParams;
			}else{
				$result .= "?".$urlParams;
			}
		}
		
		$hash = md5($this->secret . $hash . $result);
		
		$result .= (strpos($result, "?") ? "&h=" . $hash : "?h=" . $hash);
		
		return $result;
	}
}