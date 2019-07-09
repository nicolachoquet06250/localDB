<?php


class ModelSaver {
	use ModelAction;
	/** @var Collection $collection */
	private $collection;

	public function __construct($datas, $collection) {
		$this->datas = $datas;
		$this->collection = $collection;
	}

	public function save() {
		return file_put_contents($this->collection->getCompleteCollectionPath(Collection::BSON_FORMAT), $this->toBson());
	}
}