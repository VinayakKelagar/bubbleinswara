<?php
require_once (APPPATH.'/libraries/JsonRPCClient.php');
class Client
{
	private $uri;
	private $jsonrpc;
	private $transactions;
	private $code;

	function init($host="127.0.0.1", $port="11079", $user="rpcuserdfsdf34354", $pass="rpcpasswor52kewjrh34kjer234")
	{
		$this->uri = "http://" . $user . ":" . $pass . "@" . $host . ":" . $port . "/";
		$this->jsonrpc = new jsonRPCClient($this->uri);
		$this->code = "LTCC";
		return $this;
	}

	function getBalance($user_session)
	{
		return $this->jsonrpc->getbalance("{$this->code}(" . $user_session . ")",6);
	}

	function getAddressList($user_session)
	{
		return $this->jsonrpc->getaddressesbyaccount("{$this->code}(" . $user_session . ")");		
	}

	function getTransactionList($user_session)
	{
		if($this->transactions==null)
		{
			$this->transactions = $this->jsonrpc->listtransactions("{$this->code}(" . $user_session . ")", 99999999999);
		}
		return $this->transactions;
	}

	function getBalTransactionList($user_session)
	{
		return $this->getTransactionList($user_session);
	}

	function getNewAddress($user_session)
	{
		//return $this->jsonrpc->getnewaddress($user_session);
		return $this->jsonrpc->getnewaddress("{$this->code}(" . $user_session . ")");
		//return "1test";
	}

	function withdraw($user_session, $address, $amount)
	{
		return $this->jsonrpc->sendfrom("{$this->code}(" . $user_session . ")", $address, (float)$amount, 6);
		//return "ok wow";
	}
	
	function sendmany($user_session, $address, $amount)
	{
		return $this->jsonrpc->sendmany("{$this->code}(" . $user_session . ")", $address, (float)$amount, 6);
		//return "ok wow";
	}
	
	function validateaddress($address)
	{
		return $this->jsonrpc->validateaddress($address);
		//return "ok wow";
	}
	function getlasttxs($address)
	{
		return $this->jsonrpc->getlasttxs($address);
		//return "ok wow";
	}
	
	// New Create
	function getinfo()
	{
		return $this->jsonrpc->getinfo();
		//return "ok wow";
	}
	
	function getblock($hash)
	{
		return $this->jsonrpc->getblock($hash);
		//return "ok wow";
	}
	
	function getblockcount()
	{
		return $this->jsonrpc->getblockcount();
		//return "ok wow";
	}
	function getconnectioncount()
	{
		return $this->jsonrpc->getconnectioncount();
		//return "ok wow";
	}
	
	function gettransaction($txid)
	{
		return $this->jsonrpc->gettransaction($txid);
	}
	
	function listaccounts()
	{
		return $this->jsonrpc->listaccounts();
		//return "ok wow";
	}
	function listreceivedbyaccount()
	{
		return $this->jsonrpc->listreceivedbyaccount();
		//return "ok wow";
	}
	function listreceivedbyaddress()
	{
		return $this->jsonrpc->listreceivedbyaddress();
		//return "ok wow";
	}
	function listtransactions($user_session)
	{
		return $this->jsonrpc->listtransactions("{$this->code}(" . $user_session . ")", 10);
	}
	
	function getaddressesbyaccount($user_session)
	{
		return $this->jsonrpc->getaddressesbyaccount("{$this->code}(" . $user_session . ")");
		//return array("1test", "1test");
	}
	
	function getdifficulty()
	{
		return $this->jsonrpc->getdifficulty();
	}
}
?>