<?php

namespace Ianrizky\MoslemPray;

use Ianrizky\MoslemPray\Contracts\Collection as CollectionContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class AbstractCollection extends Collection implements CollectionContract
{
    /**
     * Create a new instance class.
     *
     * @param  mixed  $items
     * @param  bool  $removeItemOnEmptyValue
     * @return void
     */
    public function __construct($items = [], bool $removeItemOnEmptyValue = true)
    {
        $this->items = $this->collectItems(
            $this->getArrayableItems($items), $removeItemOnEmptyValue
        );
    }

    /**
     * Collect items based on specified list of class attribute key.
     *
     * @param  array  $originals
     * @param  bool  $removeItemOnEmptyValue
     * @return array
     */
    protected function collectItems(array $originals, bool $removeItemOnEmptyValue = true): array
    {
        $attributes = collect($this->getAttributes($originals));

        $items = $attributes->map(function ($type, $key) use ($originals) {
            if (!array_key_exists($key, $originals)) {
                return;
            }

            if (is_string($type) && Str::endsWith($type, '[]') && Arr::accessible($originals[$key])) {
                $type = Str::before($type, '[]');

                return collect(array_map(function ($original) use ($type) {
                    return $this->returnItem($original, $type);
                }, $originals[$key]));
            }

            return $this->returnItem($originals[$key], $type);
        });

        if ($removeItemOnEmptyValue) {
            $items = $items->filter(function ($item) {
                return !is_null($item);
            });
        }

        return $this->items = $items->all();
    }

    /**
     * Return item based on specified data type.
     *
     * @param  mixed  $item
     * @param  mixed  $type
     * @return mixed
     */
    protected function returnItem($item, $type)
    {
        switch (true) {
            case is_callable($type):
                return $type($item);

            case class_exists($type):
                return new $type($item);

            case $type === 'int' && is_numeric($item):
                return (int) $item;

            case $type === 'bool' && is_bool($item):
                return (bool) $item;

            case $type === 'string' && is_string($item):
            default:
                return $item;
        }
    }

    /**
     * Return attribute key name for the class.
     *
     * @return string
     */
    public static function getKeyName(): string
    {
        $class = new static;

        if (!isset($class->key)) {
            return str_replace(
                '\\', '', Str::snake(Str::plural(class_basename($class)))
            );
        }

        return $class->key;
    }

    /**
     * Return list of attribute key with it's data type.
     *
     * @param  array  $originals
     * @return array
     */
    public function getAttributes(array $originals): array
    {
        if (method_exists($this, 'attributes')) {
            return $this->attributes($originals);
        }

        return $this->attributes;
    }

    /**
     * Set a given item on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setItem($key, $value)
    {
        if ($this->isItemHasSetMutator($key)) {
            return $this->setMutatedItemValue($key, $value);
        }

        return $this->put($key, $value);
    }

    /**
     * Determine if a set mutator exists for an item.
     *
     * @param  string  $key
     * @return bool
     */
    protected function isItemHasSetMutator($key): bool
    {
        return method_exists($this, 'set'.Str::studly($key));
    }

    /**
     * Set the value of an item using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function setMutatedItemValue($key, $value)
    {
        return $this->{'set'.Str::studly($key)}($value);
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            if ($value instanceof Carbon) {
                return $value->format(static::DATE_FORMAT);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->items);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof Carbon) {
                return $value->format(CollectionContract::DATE_FORMAT);
            } elseif ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->items);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setItem($key, $value);
    }
}
