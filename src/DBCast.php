<?php

declare(strict_types=1);

namespace SavvyWombat\LaravelTestUtils;

class DBCast
{
    /**
     * Generate a raw DB query to cast an array or json string to a JSON field.
     *
     * @param array|string $json
     * @throws \Exception
     * @return \Illuminate\Database\Query\Expression
     */
    public static function toJson($json)
    {
        if (is_array($json)) {
            $json = addslashes(json_encode($json));
        } elseif (is_null($json) || is_null(json_decode($json))) {
            throw new \Exception('A valid JSON string was not provided.');
        }
        return \DB::raw("CAST('{$json}' AS JSON)");
    }

    /**
     * Casts a DateTime (or compatible object) into a timestamp.
     *
     * @param \DateTime
     * @return string
     */
    public static function toTimestamp($dateTime)
    {
        return $dateTime->format("Y-m-d H:i:s");
    }
}