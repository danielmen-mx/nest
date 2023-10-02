<?php

namespace App\Facades\Base;

use Ramsey\Uuid\Uuid;

class Conversion
{
    public function toBoolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /*
     * It formats a uuid string to a valid uuid format, for example 0073441B9C7F4EB2BC14747699EB96AF to
     * @param uuid The uuid to format
     * @return string The formatted uuid
     *
    */
    public function formatUuid($uuid)
    {
        return Uuid::fromString($uuid)->toString();
    }

    public function stringToArray($string, $dictionary = null, $delimiter = ',')
    {
        if (!$string) {
            return;
        }

        if (is_array($string)) {
            if (count($string) == 0) {
                return null;
            }

            return $string;
        }

        $array = collect(explode($delimiter, $string))->map(function ($string) {
            return trim($string);
        })->filter(function ($string) {
            return $string ? true : false;
        })->map(function ($string) use ($dictionary) {
            if ($dictionary) {
                $string = $dictionary[$string];
            }

            return $string;
        });

        return $array->count() > 0 ? $array->toArray() : null;
    }

    public function stringToCollect($string, $dictionary = null)
    {
        $toCollect = $this->stringToArray($string, $dictionary);
        return $toCollect ? collect($toCollect) : null;
    }

    protected function getIdFromUuid($uuid, $model, $singular = true, $idColumn = 'id', $uuidColumn = 'uuid', $returnQuery = false)
    {
        if (!$uuid) {
            return null;
        }

        $uuid = is_string($uuid) ? $uuid = $this->stringToArray($uuid) : $uuid;

        if ($returnQuery) {
            return $model::whereIn($uuidColumn, $uuid)->select($idColumn);
        }

        $validUuids = collect($uuid)->filter(fn ($item) => !is_numeric($item));

        $invalidUuids = collect($uuid)->filter(fn ($item) => is_numeric($item))->values();

        $ids = collect();

        if ($validUuids->count() > 0) {
            $ids = $model::whereIn($uuidColumn, $validUuids->toArray())
                ->select($idColumn)
                ->get()
                ->pluck('id');
        }

        $ids = $ids->concat($invalidUuids)->unique()->toArray();

        if ($singular) {
            return isset($ids[0]) ? $ids[0] : null;
        }

        return count($ids) ? $ids : null;
    }

    protected function getUuidFromId($id, $model, $singular = true, $uuidColumn = 'uuid', $idColumn = 'id', $returnQuery = false)
    {
        if (!$id) {
            return null;
        }

        $id = is_string($id) ? $id = $this->stringToArray($id) : $id;

        if ($returnQuery) {
            return $model::whereIn($idColumn, $id)->select($uuidColumn);
        }

        $validIds = collect($id)->filter(fn ($item) => is_numeric($item))->values();

        $invalidIds = collect($id)->filter(fn ($item) => !is_numeric($item));

        $uuids = collect();

        if ($validIds->count() > 0) {
            $uuids = $model::whereIn($idColumn, $validIds->toArray())
                ->select($uuidColumn)
                ->get()
                ->pluck('uuid');
        }

        $uuids = $uuids->concat($invalidIds)->unique()->toArray();

        if ($singular) {
            return isset($uuids[0]) ? $uuids[0] : null;
        }

        return count($uuids) ? $uuids : null;
    }

    public function uuidsToId($uuid, $model, $idColumn = 'id', $uuidColumn = 'uuid', $returnQuery = false)
    {
        return $this->getIdFromUuid($uuid, $model, false, $idColumn, $uuidColumn, $returnQuery);
    }

    public function uuidToId($uuid, $model, $idColumn = 'id', $uuidColumn = 'uuid', $returnQuery = false)
    {
        return $this->getIdFromUuid($uuid, $model, true, $idColumn, $uuidColumn, $returnQuery);
    }

    public function idToUuid($id, $model, $uuidColumn = 'uuid', $idColumn = 'id', $returnQuery = false)
    {
        return $this->getUuidFromId($id, $model, true, $uuidColumn, $idColumn, $returnQuery);
    }

    public function toCents($value)
    {
        $cents = round($value * 100, 2);
        return intval($cents);
    }
}
