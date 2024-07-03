<?php

namespace Phpdominicana\Lightwave\Traits;

trait AssetResolver
{
    private array $manifest = [];
    private string $manifestPath = './assets/manifest.json';

    /**
     * Resolve the asset path.
     * 
     * @param string $path
     * @return string
     */
    private function resolve(string $path): string
    {
        $url = '';

        if (!empty($this->manifest["resources/{$path}"])) {
            $url = 'assets/' . $this->manifest["resources/{$path}"]['file'];
        }

        return $url;
    }

    /**
     * Get the CSS bundle URL.
     * 
     * @return string
     */
    private function getCssBundleUrl(): string
    {
        return $this->resolve('css/main.css');
    }


    /**
     * Get the JavaScript bundle URL.
     * 
     * @return string
     */
    private function getJsBundleUrl(): string
    {
        return $this->resolve('js/main.js');
    }

    /**
     * Generate the CSS tag.
     * 
     * @param string $url
     * @return string
     */
    private function generateCssTag(string $url): string
    {
        return '<link rel="stylesheet" href="' . $url . '">';
    }

    /**
     * Generate the JavaScript tag.
     * 
     * @param string $url
     * @return string
     */
    private function generateJsTag(string $url): string
    {
        return '<script type="module" src="' . $url . '"></script>';
    }

    /**
     * Generate the development JavaScript tag.
     * 
     * @return string
     */
    private function generateDevjsTag(): string
    {
        return '<script type="module" src="http://localhost:'.env('VITE_SERVER_PORT').'/resources/js/main.js"></script>';
    }

    /**
     * Get the CSS bundle.
     * 
     * @return string
     */
    public function getCssBundle(): string
    {
        $url = $this->getCssBundleUrl();

        if (env('APP_ENV') === 'development') {
            return $this->generateCssTag('http://localhost:'.env('VITE_SERVER_PORT').'/resources/css/main.css');
        }

        $linkTag = $this->generateCssTag($url);

        return $linkTag;
    }

    /**
     * Get the JavaScript bundle.
     * 
     * @return string
     */
    public function getJsBundle(): string
    {
        $url = $this->getJsBundleUrl();

        if (env('APP_ENV') === 'development') {
            return $this->generateDevjsTag($url);
        }

        $script = $this->generateJsTag($url);

        return $script;
    }

    /**
     * Get the assets.
     * 
     * @return array
     */
    public function getAssets(): array
    {
        if (empty($this->manifestPath) || !file_exists($this->manifestPath)) {
            die('Run <code>npm run build</code> in your application root!');
        }

        $this->manifest = json_decode(file_get_contents($this->manifestPath), true);

        return [
            'cssBundle' => $this->getCssBundle(),
            'jsBundle' => $this->getJsBundle(),
        ];
    }
}
