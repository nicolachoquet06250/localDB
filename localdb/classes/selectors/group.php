<?php


class group extends NoSQLSelector {

	public function parse(): void {
		$groups = [];
		$group_key = $this->selectorDatas['by'];
		foreach ($this->datas as $data) {
			if(!in_array($data->$group_key, array_keys($groups))) {
				$groups[$data->$group_key] = [];
			}
		}

		foreach ($this->datas as $data) {
			if(in_array($data->$group_key, array_keys($groups))) {
				$groups[$data->$group_key][] = $data;
			}
		}

		$this->datas = $groups;
	}
}