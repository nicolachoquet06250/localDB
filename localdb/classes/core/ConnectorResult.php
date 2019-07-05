<?php


class ConnectorResult {
	/** @var string|null $error */
	private $error;
	/** @var Client|null $client */
	private $client;

	/**
	 * ConnectorResult constructor.
	 *
	 * @param string|null $error
	 * @param string|null $client
	 */
	public function __construct($error = null, $client = null) {
		$this->error = $error;
		$this->client = $client;
	}

	public function then(callable $callback) {
		if(!$this->error) {
			$callback($this->client);
		}
		return $this;
	}

	public function catch(callable $callback) {
		if($this->error) {
			$callback($this->error);
		}
		return $this;
	}
}