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

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if (strpos($content, '<?= $content ?>') === false) {
            if (!isset($layout) || $layout !== false) {
                $layout = $layout ?? 'layouts.main';
                $layoutFile = BASE_PATH . '/resources/views/' . str_replace('.', '/', $layout) . '.php';
                
                if (file_exists($layoutFile)) {
                    ob_start();
                    include $layoutFile;
                    echo ob_get_clean();  // <-- echo here
                    return;               // stop after echoing layout
                }
            }
        }

        echo $content;  // <-- echo here
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
