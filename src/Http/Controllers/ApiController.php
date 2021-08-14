<?php
/**
 * MYMO CMS - The Best Laravel CMS
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 7/17/2021
 * Time: 12:28 PM
 */

namespace Juzaweb\Core\Http\Controllers;

use Illuminate\Support\MessageBag;

class ApiController extends Controller
{
    protected function restSuccess($data, string $message = '', int $status = 200)
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    protected function restFail($errors, string $message = '', int $status = 422)
    {
        switch (true) {
            case $errors instanceof MessageBag:
                $errorsResult = [];
                foreach ($errors->getMessages() as $field => $items) {
                    foreach ($items as $error) {
                        $errorsResult[] = [
                            'field' => $field,
                            'message' => $error
                        ];
                    }
                }
                break;
            default:
                $errorsResult = $errors;
                break;
        }
        $response = [
            'errors' => $errorsResult,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    protected function restPaginate($paginate, string $message = '', int $status = 200)
    {
        $currentPage = $paginate->currentPage();
        $lastPage = $paginate->lastPage();
        $response = [
            'data' => $paginate->items(),
            'links' => [
                'self' => $paginate->url($currentPage),
                'first' => $paginate->url(1),
                'prev' => $paginate->previousPageUrl(),
                'next' => $paginate->nextPageUrl(),
                'last' => $paginate->url($lastPage)
            ],
            'meta' => [
                'totalPages' => $lastPage,
                'limit' => $paginate->perPage(),
                'total' => $paginate->total(),
                'page' => $currentPage
            ],
            'message' => $message
        ];

        return response()->json($response, $status);
    }
}