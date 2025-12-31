<?php

class Controller {
    protected function view($view, $data = []) {
        // Stop render HTML untuk AJAX request
        if (defined('IS_AJAX') && IS_AJAX) {
            return; // ⛔ STOP render HTML untuk AJAX
        }
        View::render($view, $data);
    }

    protected function json($data, $statusCode = 200) {
        // Clear ALL output buffers first
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Log for debugging
        error_log("Controller::json() called - Status: $statusCode");
        
        // Set headers - MUST be before any output
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8', true);
        header('Cache-Control: no-cache, must-revalidate', true);
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT', true);
        
        // Output JSON
        $jsonOutput = json_encode($data, JSON_UNESCAPED_UNICODE);
        error_log("Controller::json() output: " . substr($jsonOutput, 0, 200));
        
        // Clear any remaining buffers before output
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        echo $jsonOutput;
        
        // Exit immediately - this prevents any further code execution
        exit(0);
    }

    protected function redirect($url) {
        Response::redirect($url);
    }

    protected function back() {
        Response::back();
    }
}
