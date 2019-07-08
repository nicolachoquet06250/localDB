<?php


class Client {
	private $connector;
	private $collections;

	public function __construct(Connector $connector) {
		$this->connector = $connector;
	}

	/**
	 * @throws ReflectionException
	 */
	public function affectThisToAllModels() {
		foreach (Model::getAll() as $model) {
			$model::setClient($this);
		}
	}

	public function close() {}

	public function setConnections(array $collections): void {
		$this->collections = $collections;
	}

	public function hasCollection(string $collection): bool {
		return (new Collection($this->connector->getDbDirectory(), $collection))->exists();
	}

	/**
	 * @param string $collection
	 * @return Collection
	 * @throws ReflectionException
	 */
	public function collection(string $collection): Collection {
		$_collection = $this->hasCollection($collection) ? $this->collections[$collection] : null;

		if(is_null($_collection)) {
			$_collection = new Collection($this->connector->getDbDirectory(), $collection);
			$this->collections[$collection] = $_collection->create();
		}
		return $_collection;
	}
}