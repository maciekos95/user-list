<?php

namespace App\Controllers;

use Framework\Classes\Lang;
use Framework\Classes\View;
use Framework\Classes\Request;
use Framework\Classes\Response;
use Framework\Classes\Session;
use Framework\Enums\HttpStatusCode;
use Framework\Enums\MessageType;
use App\Models\User;

class HomeController
{
    /**
     * Display the home page with a list of users.
     *
     * @param Request $request The request object.
     * @return Response The rendered view content as a Response object.
     */
    public function index(Request $request): Response
    {
        $messages = Session::getMessages();
        Session::removeMessages();

        if (!$users = User::getAll()) {
            $messages[] = [
                'type' => MessageType::INFO,
                'content' => Lang::get('messages.no_users_found'),
            ];
        }

        return View::render('main', [
            'partialFile' => 'list', 
            'subpageTitle' => Lang::get('app.main.home_page_title'),
            'messages' => $messages,
            'users' => $users ?? [],
        ]);
    }

    /**
     * Display the 404 Not Found page.
     *
     * @param Request $request The request object.
     * @return Response The rendered view content as a Response object.
     */
    public function notFound(Request $request): Response
    {
        $messages = Session::getMessages();
        Session::removeMessages();

        $messages[] = [
            'type' => MessageType::ERROR,
            'content' => Lang::get('messages.page_not_found'),
        ];

        return View::render('main', [
            'subpageTitle' => Lang::get('app.main.page_not_found_title'),
            'messages' => $messages,
        ]);
    }

    /**
     * Set the language cookie based on the POST request parameter.
     *
     * @param Request $request The request object.
     * @return Response The response object.
     */
    public function setLanguage(Request $request): Response
    {
        if (Lang::set($request->input('language'))) {
            $messageType = MessageType::SUCCESS;
            $message = Lang::get('messages.language_set_ok');
            $status = HttpStatusCode::OK;
        } else {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.failed_to_set_language');
            $status = HttpStatusCode::BAD_REQUEST;
        }

        Session::setMessage($messageType, $message);
        return Response::make($message, $status);
    }
}
