<?php

namespace DenizTezcan\BolRetailerV3\Support;

use Exception;

class Serialize
{
    public static function deserialize(string $input): object
    {
        $json = json_decode($input);

        if (!is_object($json)) {
            throw new Exception('Failed to deserialize: '.$input);
        }

        return $json;
    }

    public static function toPhpVariableConvention(string $input)
    {
        if (strtoupper($input) == $input) {
            return strtolower($input);
        }

        return lcfirst($input);
    }

    public static function toNativeType(string $input)
    {
        if (is_numeric($input) && strpos($input, '.') !== false) {
            return (float) $input;
        }
        if (is_numeric($input)) {
            return (int) $input;
        }

        return $input;
    }

    public static function deserializeCSV(string $input): array
    {
        $csv = str_getcsv($input, "\n");
        $headerRow = array_map('self::toPhpVariableConvention', str_getcsv($csv[0]));

        array_walk($csv, function (&$a) use ($headerRow) {
            $a = array_combine($headerRow, str_getcsv($a));
            $a = array_map('self::toNativeType', $a);
        });

        array_shift($csv);

        return $csv;
    }
}
