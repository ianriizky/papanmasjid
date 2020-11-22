<?php

namespace Ianrizky\MoslemPray\Response\Banghasan;

use Ianrizky\MoslemPray\Response\AbstractResponse;

/**
 * @property int $id
 * @property string $name
 */
class City extends AbstractResponse
{
    /**
     * List of attribute key with it's data type.
     *
     * @var array
     */
    public array $attributes = [
        'id' => 'int',
        'name' => 'string',
    ];
}
