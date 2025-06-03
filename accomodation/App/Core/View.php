<?php

namespace App\Core;

/**
 * View class
 * Handles rendering of views
 */
class View
{
    /**
     * Render a view
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public static function render($view, $data = [])
    {
        $viewPath = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }
        
        // Extract data to make it available in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view
        include $viewPath;
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Render a view with a layout
     *
     * @param string $view
     * @param array $data
     * @param string $layout
     * @return string
     */
    public static function renderWithLayout($view, $data = [], $layout = 'main')
    {
        $content = self::render($view, $data);
        
        return self::render("layouts/{$layout}", array_merge($data, [
            'content' => $content
        ]));
    }

    /**
     * Output a view
     *
     * @param string $view
     * @param array $data
     * @param string|null $layout
     * @return void
     */
    public static function output($view, $data = [], $layout = 'main')
    {
        if ($layout) {
            echo self::renderWithLayout($view, $data, $layout);
        } else {
            echo self::render($view, $data);
        }
    }
}
