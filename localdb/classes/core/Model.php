<?php


class Model {
	public function __construct(array $props = []) {
		$this->cast($props);
	}

	private function getCollectionName() {
		$class = get_class($this);
		$class = explode('\\', $class);
		$class = $class[count($class) - 1];
		$class = preg_replace_callback('/([A-Z][a-z]+)/', function ($matches) {
			return strtolower($matches[1]).'_';
		}, $class);
		$class = substr($class, 0, strlen($class) - 1);
		return $class;
	}

	public function toCollectionItem() {
		$object = new stdClass();
		foreach ($this->toArray() as $key => $val) {
			$object->$key = $val;
		}
		return $object;
	}

	public function cast($object): self {
		if(gettype($object) === 'array') {
			foreach ($object as $key => $value) {
				if(in_array($key, array_keys(get_class_vars(get_class($this))))) {
					$this->$key = $value;
				}
			}
		}
		elseif (gettype($object) === 'object') {
			foreach (get_object_vars($object) as $key => $value) {
				if(in_array($key, array_keys(get_class_vars(get_class($this))))) {
					$this->$key = $value;
				}
			}
		}
		return $this;
	}

	public function toArray() {
		$array = [];
		foreach ($this as $key => $val) {
			$array[$key] = $val;
		}
		return $array;
	}

	public function save(Client $client) {
		return $client->collection($this->getCollectionName())->add($this);
	}
}