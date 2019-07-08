<?php


class where extends NoSQLSelector {

	const OPERATOR_EQUAL = '=';
	const OPERATOR_DIFFERENT = '!=';
	const OPERATOR_SUPERIOR = '>';
	const OPERATOR_SUPERIOR_OR_EQUAL = '>=';
	const OPERATOR_INFERIOR = '<';
	const OPERATOR_INFERIOR_OR_EQUAL = '<=';

	public function parse(): void {
		$result = [];
		if(isset($this->selectorDatas['match'])) {
			$field = isset($this->selectorDatas['match']['field'])
				? $this->selectorDatas['match']['field'] : $this->selectorDatas['match'][0];
			$regex = isset($this->selectorDatas['match']['regex'])
				? $this->selectorDatas['match']['regex'] : $this->selectorDatas['match'][1];
			$reverse = isset($this->selectorDatas['match']['reverse'])
				? $this->selectorDatas['match']['reverse'] : $this->selectorDatas['match'][2];
			foreach ($this->datas as $data) {
				preg_match($regex, $data->$field, $matches);
				$cond = $reverse ? empty($matches) : !empty($matches);
				if($cond) $result[] = $data;
			}
		}
		else {
			foreach ($this->datas as $data) {
				$cond_results = [];
				foreach ($this->selectorDatas as $cond) {
					$field    = $cond[0];
					$operator = $cond[1];
					$value    = $cond[2];
					switch ($operator) {
						case self::OPERATOR_EQUAL:
							$cond_result = $data->$field === $value;
							break;
						case self::OPERATOR_DIFFERENT:
							$cond_result = $data->$field !== $value;
							break;
						case self::OPERATOR_SUPERIOR:
							$cond_result = $data->$field > $value;
							break;
						case self::OPERATOR_INFERIOR:
							$cond_result = $data->$field < $value;
							break;
						case self::OPERATOR_SUPERIOR_OR_EQUAL:
							$cond_result = $data->$field >= $value;
							break;
						case self::OPERATOR_INFERIOR_OR_EQUAL:
							$cond_result = $data->$field <= $value;
							break;
						default:
							$cond_result = false;
							break;
					}
					$cond_results[] = $cond_result;
				}
				if (!in_array(false, $cond_results)) {
					$result[] = $data;
				}
			}
		}
		$this->datas = $result;
	}
}