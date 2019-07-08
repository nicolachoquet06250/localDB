<?php


abstract class NoSQLSelector {
	protected $datas;
	protected $selectorDatas;
	public function __construct($selectorDatas, $datas) {
		$this->selectorDatas = $selectorDatas;
		$this->datas = $datas;
	}

	public static function requireAll() {
		foreach (get_declared_classes() as $class) {
			$class_ref = new ReflectionClass($class);
			if($class_ref->getParentClass() && $class_ref->getParentClass()->name === 'NoSQLSelector') {
				Collection::addSelector($class_ref->getName());
			}
		}
	}

	public abstract function parse(): void ;

	public function get() {
		return $this->datas;
	}
}