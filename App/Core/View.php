<?php

namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        
        $viewFile = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: $viewFile");
        }

        // Start output buffering
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If the view doesn't use a layout, return content directly
        if (strpos($content, '<?= $content ?>') === false) {
            // Check if we need to wrap in layout
            if (!isset($layout) || $layout !== false) {
                $layout = $layout ?? 'layouts.main';
                $layoutFile = BASE_PATH . '/resources/views/' . str_replace('.', '/', $layout) . '.php';
                
                if (file_exists($layoutFile)) {
                    ob_start();
                    include $layoutFile;
                    return ob_get_clean();
                }
            }
        }

        return $content;
    }

    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }
}
