<?php

class count extends NoSQLSelector {

	public function parse(): void {
		$this->datas = gettype($this->datas === 'array') ? count($this->datas) : count(array_keys(get_object_vars($this->datas)));
	}
}