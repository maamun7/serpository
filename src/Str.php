<?php

namespace Maamun7\Serpository;

use Illuminate\Support\Str as LaravelStr;

class Str
{
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     *
     * @return string
     */
    public static function studly(string $value): string
    {
        return LaravelStr::studly($value);
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     *
     * @return string
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        return LaravelStr::snake($value, $delimiter);
    }

    /**
     * Get the given name formatted as a service name.
     *
     * @param string $name
     *
     * @return string
     */
    public static function service(string $name): string
    {
        return self::studly($name) . 'Service';
    }

    /**
     * Get the given name formatted as a repository name.
     *
     * @param string $name
     *
     * @return string
     */
    public static function repository(string $name): string
    {
        return self::studly($name) . 'Repository';
    }

    /**
     * Get the given name as a Interface
     *
     * @param string $name
     *
     * @return string
     */
    public static function repositoryInterface(string $name): string
    {
        return self::repository($name) . 'Interface';
    }

    /**
     * Get repository base name
     *
     * @param string $name
     *
     * @return string
     */
    public static function model(string $name): string
    {
        return str_replace('Repository', '', $name);
    }
}
