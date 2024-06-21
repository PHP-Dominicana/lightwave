<?php


namespace Phpdominicana\Lightwave;
use Illuminate\Support\Arr;

class Config
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public static function makeFromDir(string $dir): self
    {
        $files = scandir($dir);
        $settings = [];
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $settings = array_merge($settings, [$fileName => include($dir . '/' . $file)]);
        }

        return new static($settings);
    }

    public function load(string $file): void
    {
        $this->settings = include($file);
    }

    public function get(string $key)
    {
        return Arr::get($this->settings, $key);
    }
}
