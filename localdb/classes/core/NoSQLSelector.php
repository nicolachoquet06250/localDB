<?php


abstract class NoSQLSelector {
	protected $datas;
	protected $selectorDatas;
	public function __construct($selectorDatas, $datas) {
		$this->selectorDatas = $selectorDatas;
		$this->datas = $datas;
	}

	public abstract function parse(): void ;

	public function get() {
		return $this->datas;
	}
}