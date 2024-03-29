<?php


abstract class Model {
	/**
	 * @var Client $client
	 */
	protected static $client = null;

	/**
	 * @return string[]
	 * @throws ReflectionException
	 */
	public static function getAll(): array {
		$models = [];
		foreach (get_declared_classes() as $class) {
			$class_ref = new ReflectionClass($class);
			if($class_ref->getParentClass() && $class_ref->getParentClass()->name === self::class) {
				$models[] = $class_ref->getName();
			}
		}
		return $models;
	}

	private function get_collection_name() {
		$class = get_class($this);
		$class = explode('\\', $class);
		$class = $class[count($class) - 1];
		$class = preg_replace_callback('/([A-Z][a-z]+)/', function ($matches) {
			return strtolower($matches[1]).'_';
		}, $class);
		$class = substr($class, 0, strlen($class) - 1);
		return $class;
	}

	public static function setClient(Client $client) {
		self::$client = $client;
	}

	public function __construct(array $props = []) {
		$this->cast($props);
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

	public function toJson() {
		return json_encode($this->toArray());
	}

	/**
	 * @return bool
	 * @throws ReflectionException
	 */
	public function save() {
		return $this->get_collection()->add($this);
	}

	/**
	 * @param string $field
	 * @param mixed  $value
	 * @return ModelFinder
	 * @throws ReflectionException
	 */
	public function get_from(string $field, $value) {
		return $this->get_collection()->find([ '$where' => [
			[ $field, '=', $value ]
		] ], get_class($this));
	}

	/**
	 * @param string $field
	 * @param string $regex
	 * @param bool   $reverse
	 * @return ModelFinder
	 * @throws ReflectionException
	 */
	public function get_match(string $field, string $regex, $reverse = false) {
		return $this->get_collection()->find([ '$where' => [
			'match' => [ $field, $regex, 'reverse' => $reverse ]
		] ], get_class($this));
	}

	/**
	 * @return Collection
	 * @throws ReflectionException
	 */
	public function get_collection() {
		return self::$client->collection($this->get_collection_name());
	}
}