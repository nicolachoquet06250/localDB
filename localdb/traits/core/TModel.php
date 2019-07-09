<?php


trait TModel {
	public static function test() {
		var_dump(self::getClass());
	}

	public static function getClass() {
		return self::class;
	}

	public static function getCollectionName() {
		$class = self::getClass();
		$class = explode('\\', $class);
		$class = $class[count($class) - 1];
		$class = preg_replace_callback('/([A-Z][a-z]+)/', function ($matches) {
			return strtolower($matches[1]).'_';
		}, $class);
		$class = substr($class, 0, strlen($class) - 1);
		return $class;
	}

	/**
	 * @param string $field
	 * @param mixed  $value
	 * @return ModelFinder
	 * @throws ReflectionException
	 */
	public static function from(string $field, $value) {
		return self::$client->collection(self::getCollectionName())->find([ '$where' => [
			[ $field, '=', $value ]
		] ], self::getClass());
	}

	/**
	 * @param string $field
	 * @param string $regex
	 * @param bool   $reverse
	 * @return ModelFinder
	 * @throws ReflectionException
	 */
	public static function match(string $field, string $regex, $reverse = false) {
		return self::$client->collection(self::getCollectionName())->find([ '$where' => [
			'match' => [ $field, $regex, 'reverse' => $reverse ]
		] ], self::getClass());
	}

	/**
	 * @return ModelFinder
	 * @throws ReflectionException
	 */
	public static function all() {
		return self::$client->collection(self::getCollectionName())->find([], self::getClass());
	}
}