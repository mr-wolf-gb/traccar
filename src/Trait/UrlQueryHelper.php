<?php
/*
 * Author: WOLF
 * Name: UrlQueryHelper.php
 * Modified : lun., 12 févr. 2024 14:25
 * Description: ...
 *
 * Copyright 2024 -[GHAITH BACCARI]-[WS]-
 */

namespace MrWolfGb\Traccar\Trait;

trait UrlQueryHelper
{
    protected function prepareMultipleQuery(string $key, array $array): string
    {
        $query = "";
        if (count($array) > 1) {
            foreach ($array as $value) {
                if (array_values($array)[0] == $value) {
                    $query .= "$value&";
                } elseif (end($array) == $value) {
                    $query .= "$key=$value";
                } else {
                    $query .= "$key=$value&";
                }
            }
        } else {
            $query = array_values($array)[0];
        }
        return $query;
    }
}
