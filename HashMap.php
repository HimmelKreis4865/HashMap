<?php

namespace HashMap;

use InvalidArgumentException;
use function array_values;
use function get_class;
use function in_array;
use function is_array;
use function is_bool;
use function is_callable;
use function is_double;
use function is_float;
use function is_int;
use function is_long;
use function is_null;
use function is_object;
use function is_resource;
use function is_string;
use function strtolower;

final class HashMap {

	private const SUPPORTED_KEY_TYPES = ["str", "string", "int", "integer", "float"];
	
	/** @var string $expectedKey */
	protected $expectedKey;
	
	/** @var string $expectedValue */
	protected $expectedValue;
	
	/** @var mixed[] $values */
	protected $values = [];
	
	/**
	 * HashMap constructor.
	 *
	 * @param string $expectedKey
	 * @param string $expectedValue
	 */
	public function __construct(string $expectedKey, string $expectedValue) {
		$this->expectedKey = $expectedKey;
		
		if (!in_array(strtolower($expectedKey), self::SUPPORTED_KEY_TYPES)) throw new InvalidArgumentException("Cannot use $expectedKey as expected key value!");
		
		$this->expectedValue = $expectedValue;
	}
	
	/**
	 * Returns the value for the given key
	 *
	 * @api
	 *
	 * @param mixed $key
	 *
	 * @return mixed
	 */
	public function get($key) {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		return @$this->values[$key];
	}
	
	/**
	 * Returns the value of the key or the default value, if the key isn't found
	 *
	 * @api
	 *
	 * @param mixed $key
	 *
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getOrDefault($key, $default) {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		if ($this->contains($key)) return $this->get($key);
		return $default;
	}
	
	/**
	 * Changes the key to the given value
	 *
	 * @api
	 *
	 * @param mixed $key
	 *
	 * @param mixed $value
	 */
	public function put($key, $value): void {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		if (!$this->isCompatibleType($value, $this->expectedValue)) throw new InvalidArgumentException("Expected a value of type {$this->expectedValue}");
		$this->values[$key] = $value;
	}
	
	/**
	 * Removes a given key from the map
	 *
	 * @api
	 *
	 * @param mixed $key
	 */
	public function remove($key): void {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		if (isset($this->values[$key])) unset($this->values[$key]);
	}
	
	/**
	 * Returns the size of the map
	 *
	 * @api
	 *
	 * @return int
	 */
	public function size(): int {
		return count($this->values);
	}
	
	/**
	 * Executes a specific callable for each key in the map
	 *
	 * @notice callable format: function($key, $value) {}
	 *
	 * @api
	 *
	 * @param callable $callable
	 */
	public function forEach(callable $callable): void {
		foreach ($this->values as $key => $value) {
			$callable($key, $value);
		}
	}
	
	/**
	 * Returns an Array with values of the map
	 *
	 * @api
	 *
	 * @return mixed[]
	 */
	public function values(): array {
		return array_values($this->values);
	}
	
	/**
	 * Returns an Array with all keys as values
	 *
	 * @api
	 *
	 * @return mixed[]
	 */
	public function keySet(): array {
		return array_keys($this->values);
	}
	
	/**
	 * Returns whether there is a specific key in the map
	 *
	 * @api
	 *
	 * @param mixed $key
	 *
	 * @return bool
	 */
	public function contains($key): bool {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		return isset($this->values[$key]);
	}
	
	/**
	 * Returns whether there is a specific value in the map
	 *
	 * @api
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function containsValue($value): bool {
		if (!$this->isCompatibleType($value, $this->expectedValue)) throw new InvalidArgumentException("Expected a value of type {$this->expectedValue}");
		return in_array($value, $this->values);
	}
	
	/**
	 * Replaces a given with with the new value if the old value is not null
	 *
	 * @api
	 *
	 * @param mixed $key
	 *
	 * @param mixed $value
	 */
	public function replace($key, $value): void {
		if (!$this->isCompatibleType($key, $this->expectedKey)) throw new InvalidArgumentException("Expected a key of type {$this->expectedKey}");
		if (!$this->isCompatibleType($value, $this->expectedValue)) throw new InvalidArgumentException("Expected a value of type {$this->expectedValue}");
		
		if ($this->contains($key)) $this->put($key, $value);
	}
	
	/**
	 * Returns whether the map is empty or not
	 *
	 * @api
	 *
	 * @return bool
	 */
	public function isEmpty(): bool {
		return empty($this->values);
	}
	
	/**
	 * Resets the whole map
	 *
	 * @api
	 */
	public function clear(): void {
		$this->values = [];
	}
	
	/**
	 * Returns the whole values stored
	 *
	 * @api
	 *
	 * @return mixed[]
	 */
	public function getAll(): array {
		return $this->values;
	}
	
	/**
	 * @internal
	 *
	 * @param mixed $value
	 * @param string $type
	 *
	 * @return bool
	 */
	private function isCompatibleType($value, string $type): bool {
		switch (strtolower($type)) {
			case "str": case "string": return is_string($value);
			case "float": return is_float($value);
			case "int": case "integer": return is_int($value);
			case "callable": case "function": return is_callable($value);
			case "resource": case "res": return is_resource($value);
			case "null": return is_null($value);
			case "bool": case "boolean": return is_bool($value);
			case "object": case "obj": return is_object($value);
			case "double": return is_double($value);
			case "array": return is_array($value);
			case "long": return is_long($value);
			default: return ((is_object($value) ? (get_class($value) === $type) : false));
		}
	}
}