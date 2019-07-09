<?php


trait ModelAction {
	protected $datas;

	public function __construct($datas) {
		$this->datas = $datas;
	}

	public function is_numeric() {
		return is_numeric($this->datas);
	}

	public function is_array() {
		return is_array($this->datas);
	}

	public function is_empty() {
		return empty($this->datas);
	}

	public function is_model() {
		return is_object($this->datas);
	}

	/**
	 * @return Model[][]|Model[]|Model|integer
	 */
	public function get() {
		return $this->datas;
	}

	public function toFullArray() {
		$result = [];
		if($this->is_model()) {
			$result = $this->get() instanceof stdClass ? (array)$this->get() : $this->get()->toArray();
		}
		elseif ($this->is_array()) {
			foreach ($this->get() as $model) {
				if(is_array($model)) {
					$_result = [];
					foreach ($model as $group => $_model) {
						$_result[$group] = $_model instanceof stdClass ? (array)$_model : $_model->toArray();
					}
					$result[] = $_result;
				}
				else $result[] = $model instanceof stdClass ? (array)$model : $model->toArray();
			}
		}
		elseif ($this->is_numeric()) $result[] = $this->get();
		return $result;
	}

	public function toJson() {
		return json_encode($this->toFullArray());
	}

	public function toBson() {
		return \MongoDB\BSON\fromJSON($this->toJson());
	}
}