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

class UsersController
{
    /**
     * Display the form for adding a new user.
     *
     * @param Request $request The request object.
     * @return Response The rendered view content as a Response object.
     */
    public function formAdd(Request $request): Response
    {
        $messages = Session::getMessages();
        Session::removeMessages();

        if ($formData = Session::get('form_data')) {
            Session::remove('form_data');
        } else {
            $formData = [];
        }

        return View::render('main', [
            'partialFile' => 'form',
            'subpageTitle' => Lang::get('app.form.add_page_title'),
            'messages' => $messages,
            'formData' => $formData,
        ]);
    }

    /**
     * Add a new user based on the submitted form data.
     *
     * @param Request $request The request object.
     * @return Response The response object.
     */
    public function add(Request $request): Response
    {
        $user = User::create($request->params);

        if ($user->save()) {
            $messageType = MessageType::SUCCESS;
            $message = Lang::get('messages.user_added');
            $status = HttpStatusCode::OK;
        } elseif ($error = $user->getFirstError()) {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.invalid_user_data');
            $status = HttpStatusCode::UNPROCESSABLE_ENTITY;
        } else {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.failed_to_save_user');
            $status = HttpStatusCode::BAD_REQUEST;
        }

        Session::setMessage($messageType, $error ?? $message);
        $response = Response::make($message, $status);

        if ($messageType == MessageType::SUCCESS) {
            $response->redirectToHomePage();
        } else {
            Session::set('form_data', $request->params);
            $response->redirectToPreviousPage();
        }

        return $response;
    }

    /**
     * Display the form for editing an existing user.
     *
     * @param Request $request The request object.
     * @param int $id The ID of the user to edit.
     * @return Response The rendered view content as a Response object.
     */
    public function formEdit(Request $request, int $id): Response
    {
        $messages = Session::getMessages();
        Session::removeMessages();

        if ($formData = Session::get('form_data')) {
            Session::remove('form_data');
        } else {
            if (!$user = User::getById($id)) {
                $messageType = MessageType::ERROR;
                $message = Lang::get('messages.user_not_found');
                $status = HttpStatusCode::BAD_REQUEST;

                Session::setMessage($messageType, $message);

                $response = Response::make($message, $status);
                $response->redirectToHomePage();

                return $response;
            }

            $formData = $user->toArray();
        }

        return View::render('main', [
            'partialFile' => 'form',
            'subpageTitle' => Lang::get('app.form.edit_page_title'),
            'messages' => $messages,
            'formData' => $formData,
        ]);
    }

    /**
     * Update an existing user based on the submitted form data.
     *
     * @param Request $request The request object.
     * @param int $id The ID of the user to edit.
     * @return Response The response object.
     */
    public function edit(Request $request, int $id): Response
    {
        if (!$user = User::getById($id)) {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.user_not_found');
            $status = HttpStatusCode::BAD_REQUEST;

            Session::setMessage($messageType, $message);

            $response = Response::make($message, $status);
            $response->redirectToPreviousPage();

            return $response;
        }

        $user->fromArray($request->params);

        if ($user->save()) {
            $messageType = MessageType::SUCCESS;
            $message = Lang::get('messages.user_edited');
            $status = HttpStatusCode::OK;
        } elseif ($error = $user->getFirstError()) {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.invalid_user_data');
            $status = HttpStatusCode::UNPROCESSABLE_ENTITY;
        } else {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.failed_to_save_user');
            $status = HttpStatusCode::BAD_REQUEST;
        }

        Session::setMessage($messageType, $error ?? $message);
        $response = Response::make($message, $status);

        if ($messageType == MessageType::SUCCESS) {
            $response->redirectToHomePage();
        } else {
            Session::set('form_data', $request->params);
            $response->redirectToPreviousPage();
        }

        return $response;
    }

    /**
     * Delete a user.
     *
     * @param Request $request The request object.
     * @param int $id The ID of the user to delete.
     * @return Response The response object.
     */
    public function delete(Request $request, int $id): Response
    {
        if (!$user = User::getById($id)) {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.user_not_found');
            $status = HttpStatusCode::BAD_REQUEST;
        } elseif (!$user->delete()) {
            $messageType = MessageType::ERROR;
            $message = Lang::get('messages.failed_to_delete_user');
            $status = HttpStatusCode::BAD_REQUEST;
        } else {
            $messageType = MessageType::SUCCESS;
            $message = Lang::get('messages.user_deleted');
            $status = HttpStatusCode::OK;
        }

        Session::setMessage($messageType, $message);
        return Response::make($message, $status);
    }
}