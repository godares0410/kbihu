<?php

class Controller {
    protected function view($view, $data = []) {
        View::render($view, $data);
    }

    protected function json($data, $statusCode = 200) {
        Response::json($data, $statusCode);
    }

    protected function redirect($url) {
        Response::redirect($url);
    }

    protected function back() {
        Response::back();
    }
}
