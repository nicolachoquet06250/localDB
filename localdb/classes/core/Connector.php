<?php


class Connector {
	private $db_directory;

	public function __construct(string $db_directory) {
		$this->db_directory = $db_directory;
	}

	public function getDbDirectory(): string {
		return $this->db_directory;
	}

	/**
	 * @param callable|null $callback
	 * @return ConnectorResult
	 */
	public function connect(callable $callback = null): ConnectorResult {
		try {
			$client = new Client($this);
			if(!is_dir($this->getDbDirectory())) {
				$db_name = explode('/', $this->getDbDirectory())[count(explode('/', $this->getDbDirectory())) - 1];
				$error_message = 'Database "'.$db_name.'" not found';
				if($callback) {
					$callback($error_message, null);
					return null;
				}
				else {
					return new ConnectorResult($error_message);
				}
			}
			$dir = opendir($this->db_directory);
			$collections = [];
			while (($elem = readdir($dir)) !== false) {
				if($elem !== '.' && $elem !== '..' && explode('.', $elem)[count(explode('.', $elem)) - 1] === 'json') {
					$file = explode('.', $elem);
					unset($file[count($file) - 1]);
					$collections[implode('.', $file)] = new Collection($this->db_directory, implode('.', $file));
				}
			}
			$client->affectThisToAllModels();
			$client->setConnections($collections);
			if ($callback) {
				$callback(false, $client);
				return null;
			} else {
				return new ConnectorResult(null, $client);
			}
		}
		catch (Exception $e) {
			return new ConnectorResult($e->getMessage());
		}
	}

	private static function DBExists(string $db_path): bool {
		return is_dir($db_path);
	}

	public static function createDB(string $db_path): Connector {
		self::DBExists($db_path) ? true : mkdir($db_path, 0777, true);
		return new Connector($db_path);
	}
}