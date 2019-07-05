<?php


class Collection {
	private $db;
	private $name;
	private static $selectors = [];

	public static function addSelector(string $selectorClass) {
		self::$selectors[] = $selectorClass;
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

	private function getCompleteCollectionPath() {
		return $this->db.'/'.$this->name.'.json';
	}

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

	protected function cast_find_options($options, $datas) {
		$keysToGet = [];
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

		// traitements
		return $datas;
	}

	/**
	 * @param stdClass|array $options
	 * @param string   $model
	 * @return Model[]|Model
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

		return $this->cast_find_options($options, $datas);
	}

	private function get() {
		return json_decode(file_get_contents($this->getCompleteCollectionPath()));
	}

	private function save($datas) {
		return file_put_contents($this->getCompleteCollectionPath(), json_encode($datas));
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