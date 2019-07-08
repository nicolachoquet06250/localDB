<?php


class ModelFinder {
	private $datas;

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
	 * @return Model[]|Model|integer
	 */
	public function get() {
		return $this->datas;
	}

	public function foreach($callback) {
		if($this->is_array()) {
			foreach ($this->get() as $k => $v) {
				$callback($v, $k);
			}
		}
		else {
			$callback($this->get(), 0);
		}
	}

	public function toFullArray() {
		$result = [];
		if($this->is_model()) {
			$result = $this->get()->toArray();
		}
		elseif ($this->is_array()) {
			foreach ($this->get() as $model) {
				$result[] = $model->toArray();
			}
		}
		elseif ($this->is_numeric()) {
			$result[] = $this->get();
		}
		return $result;
	}
}