<?php
/*
 * Author: WOLF
 * Name: ArrayToModel.php
 * Modified : mar., 13 févr. 2024 14:41
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Trait;

use Illuminate\Support\Collection;

trait ArrayToModel
{
    /**
     * @param array|string $values
     * @return static
     */
    protected static function createFromValueArray(array|string $values): static
    {
        if (is_string($values)) $values = json_decode($values, true);
        return self::getModel($values);
    }

    /**
     * @param mixed $value
     * @return static
     */
    protected static function getModel(mixed $value): static
    {
        $model = new static();
        foreach ($value as $subKey => $subValue) {
            if (is_scalar($subValue) || is_null($subValue)) {
                $model->{$subKey} = $subValue ? trim($subValue) : $subValue;
            } elseif (is_array($subValue)) {
                $subKey = $subKey == 'attributes' ? 'attribs' : $subKey;
                $model->{$subKey} = $subValue;
            } else {
                $model->{$subKey} = "unknown data";
            }
        }
        return $model;
    }

    /**
     * @param array|string $values
     * @return Collection
     */
    protected static function createFromValueList(array|string $values): Collection
    {
        $collection = collect();
        if (is_string($values)) $values = json_decode($values, true);
        foreach ($values as $key => $value) {
            $model = self::getModel($value);
            $collection->push($model);
        }
        return $collection;
    }

    /**
     * @param $model
     * @param array $data
     * @return void
     */
    private static function recursiveArrayValue(&$model, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_scalar($value) || is_null($value)) {
                $model->{$key} = $value;
            } elseif (is_array($value)) {
                self::recursiveArrayValue($model, $value);
            } else {
                $model->{$key} = "unknown data";
            }
        }
    }
}
