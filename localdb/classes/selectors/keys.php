<?php


class keys extends NoSQLSelector {

	public function parse(): void {
		$tmp = gettype($this->datas) === 'array' ? [] : new stdClass();
		if(gettype($this->datas) === 'array') {
			foreach ($this->datas as $i => $data) {
				foreach ($data as $k => $v) {
					if(!in_array($k, $this->selectorDatas)) {
						unset($data->$k);
					}
				}
				$tmp[$i] = $data;
			}
		}
		else {
			foreach (array_keys(get_object_vars($this->datas)) as $key) {
				if(in_array($key, $this->selectorDatas)) {
					$tmp->$key = $this->datas->$key;
				}
			}
		}
		$this->datas = $tmp;
	}
}