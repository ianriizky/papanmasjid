<?php

namespace Ianrizky\MoslemPray\Contracts;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface Collection extends ArrayAccess, Arrayable, Countable, Jsonable, JsonSerializable
{
    /**
     * Default date format used in this library.
     *
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d H:i:s O';

    /**
     * Return attribute key name for the class.
     *
     * @return string
     */
    public static function getKeyName(): string;

    /**
     * Return list of attribute key with it's data type.
     *
     * @param  array  $originals
     * @return array
     */
    public function getAttributes(array $originals): array;
}
