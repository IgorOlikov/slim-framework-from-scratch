<?php

namespace Framework\FastRouter\Cache;

use Closure;
use Framework\FastRouter\Cache;
use Framework\Psr\SimpleCache\CacheInterface;
use Framework\Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

final class FileCache implements Cache
{
    private const DIRECTORY_PERMISSIONS = 0775;
    private const FILE_PERMISSIONS = 0664;

    /**
     * This is cached in a local static variable to avoid instantiating a closure each time we need an empty handler
     */
    private static Closure $emptyErrorHandler;

    public function __construct()
    {
        self::$emptyErrorHandler ??= static function (): void {
        };
    }

    public function get(string $key, callable $loader): array
    {
        $result = self::readFileContents($key);

        if ($result !== null) {
            return $result;
        }

        $data = $loader();
        self::writeToFile($key, '<?php return ' . var_export($data, true) . ';');

        return $data;
    }

    /** @return ProcessedData|null */
    private static function readFileContents(string $path): ?array
    {
        // error suppression is faster than calling `file_exists()` + `is_file()` + `is_readable()`, especially because there's no need to error here
        set_error_handler(self::$emptyErrorHandler);
        $value = include $path;
        restore_error_handler();

        if (! is_array($value)) {
            return null;
        }

        // @phpstan-ignore-next-line because we won´t be able to validate the array shape in a performant way
        return $value;
    }

    private static function writeToFile(string $path, string $content): void
    {
        $directory = dirname($path);

        if (! self::createDirectoryIfNeeded($directory) || ! is_writable($directory)) {
            throw new RuntimeException('The cache directory is not writable "' . $directory . '"');
        }

        set_error_handler(self::$emptyErrorHandler);

        $tmpFile = $path . '.tmp';

        if (file_put_contents($tmpFile, $content, LOCK_EX) === false) {
            restore_error_handler();

            return;
        }

        chmod($tmpFile, self::FILE_PERMISSIONS);

        if (! rename($tmpFile, $path)) {
            unlink($tmpFile);
        }

        restore_error_handler();
    }

    private static function createDirectoryIfNeeded(string $directory): bool
    {
        if (is_dir($directory)) {
            return true;
        }

        set_error_handler(self::$emptyErrorHandler);
        $created = mkdir($directory, self::DIRECTORY_PERMISSIONS, true);
        restore_error_handler();

        return $created !== false || is_dir($directory);
    }
}