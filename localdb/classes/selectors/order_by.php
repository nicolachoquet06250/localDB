<?php


class order_by extends NoSQLSelector {
	public function parse(): void {
		$order_key = $this->selectorDatas;
		usort($this->datas, function ($data, $data1) use ($order_key) {
			return $data->$order_key < $data1->$order_key;
		});
	}
}