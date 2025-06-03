<?php

namespace App\Core;

class DotEnv
{
    public static function load($path)
    {
        // Assume $path is the directory, append .env
        $filePath = rtrim($path, '/') . '/.env';
        
        if (!file_exists($filePath)) {
            error_log("DotEnv: .env file does not exist at: $filePath");
            throw new \Exception("The .env file does not exist at: $filePath");
        }

        if (!is_readable($filePath)) {
            error_log("DotEnv: .env file is not readable at: $filePath");
            throw new \Exception("The .env file is not readable at: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            error_log("DotEnv: Failed to read .env file at: $filePath");
            throw new \Exception("Failed to read .env file at: $filePath");
        }

        foreach ($lines as $line) {
            // Skip comments and empty lines
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Split on first '=' only
            if (strpos($line, '=') === false) {
                error_log("DotEnv: Skipping invalid .env line: $line");
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove quotes if present
            if (preg_match('/^"(.*)"$/', $value, $matches)) {
                $value = $matches[1];
            } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }

            if (!array_key_exists($name, $_ENV)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value; // Align with previous behavior
            }
        }
    }
}