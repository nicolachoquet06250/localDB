<?php


class Collection {
	private $db;
	private $name;
	private static $selectors = [];

	const JSON_FORMAT = 'json';
	const BSON_FORMAT = 'bson';

	public static function addSelector(string $selectorClass) {
		if(!in_array($selectorClass, self::$selectors)) {
			self::$selectors[] = $selectorClass;
		}
	}

	/**
	 * @param string[] $selectorClasses
	 */
	public static function addSelectors(...$selectorClasses) {
		foreach ($selectorClasses as $selectorClass) {
			self::addSelector($selectorClass);
		}
	}

	public static function getSelectors() {
		return self::$selectors;
	}

	public static function getSelector($selector, $datas) {
		return new self::$selectors[$selector]($datas);
	}

	private static function fromBson($data) {
		$data = self::fromJson(\MongoDB\BSON\toJSON($data), true);
		return $data;
	}

	private static function fromJson($data, $assoc = false) {
		return json_decode($data, $assoc);
	}

	public function getCompleteCollectionPath($format = self::JSON_FORMAT) {
		return $this->db.'/'.$this->name.'.'.$format;
	}

	/**
	 * Collection constructor.
	 *
	 * @param string $db_path
	 * @param string $name
	 * @throws ReflectionException
	 */
	public function __construct(string $db_path, string $name) {
		$this->db = $db_path;
		$this->name = $name;
	}

	public function create(): Collection {
		$this->save(new stdClass());
		return $this;
	}

	public function exists(): bool {
		return is_file($this->getCompleteCollectionPath());
	}

	/**
	 * @param $options
	 * @param $datas
	 * @return Model[]|Model
	 */
	protected function cast_find_options($options, $datas) {
		if(gettype($options) === 'object') {
			$_options = [];
			foreach (get_object_vars($options) as $key => $val) {
				$_options[$key] = $val;
			}
			$options = $_options;
		}
		if(empty($options)) {
			return $datas;
		}

		foreach ($options as $option => $data) {
			$_opt = str_replace('$', '', $option);
			/** @var NoSQLSelector $selector */
			$selector = new $_opt($data, $datas);
			$selector->parse();
			$datas = $selector->get();
		}

		if(is_array($datas) && count($datas) === 1) {
			return $datas[0];
		}
		return $datas;
	}

	/**
	 * @param stdClass|array $options
	 * @param string   $model
	 * @return ModelFinder
	 */
	public function find($options, string $model) {
		/** @var Model $model */
		$datas = $this->get();
		if(gettype($datas) === 'array') {
			$results = [];
			foreach ($datas as $data) {
				$model = new $model();
				$results[] = $model->cast($data);
			}
			$datas = $results;
		}
		else {
			$model = new $model();
			$datas = $model->cast($datas);
		}

		return new ModelFinder($this->cast_find_options($options, $datas));
	}

	private function get() {
		$is_dev = defined('DEV') && DEV === true;
		$data = file_get_contents($this->getCompleteCollectionPath(
			($is_dev ? self::JSON_FORMAT : self::BSON_FORMAT)
		));
		return $is_dev ? self::fromJson($data) : self::fromBson($data);
	}

	public function save($datas) {
		return defined('DEV') && DEV === true ?
			file_put_contents($this->getCompleteCollectionPath(), json_encode($datas))
			: (new ModelSaver($datas, $this))->save();
	}

	protected function size($object = null) {
		$cmp = 0;
		$object = $object ?? $this->get();
		foreach ($object as $val) {
			$cmp++;
		}
		return $cmp;
	}

	public function add(Model $model): bool {
		$all_datas = $this->get();
		if(gettype($all_datas) === 'object') {
			if($this->size() === 0) {
				$all_datas = $model->toCollectionItem();
			}
			else {
				$all_datas = [$all_datas];
				$all_datas[] = $model->toCollectionItem();
			}
		}
		else {
			$all_datas[] = $model->toCollectionItem();
		}
		return $this->save($all_datas);
	}
}