<?php

namespace Framework\Classes;

class View
{
    /**
     * Renders the view.
     *
     * @param string $viewFile The view file to be rendered.
     * @param array $data The data to be passed to the view.
     * @return Response The rendered view content as a Response object.
     */
    public static function render(string $viewFile, array $data = []): Response
    {
        ob_start();
        extract($data);
        require "resources/views/$viewFile.php";
        $content = ob_get_clean();

        return Response::make($content);
    }
}
