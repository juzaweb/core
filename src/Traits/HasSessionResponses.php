<?php

namespace Juzaweb\Core\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HasSessionResponses
{
    /**
     * Handle response
     *
     * @param array|string $data
     * @param bool $success
     * @return JsonResponse|RedirectResponse
     */
    protected function response(array|string $data, bool $success = true): JsonResponse|RedirectResponse
    {
        if (! is_array($data)) {
            $data = ['message' => $data];
        }

        if (request()->has('redirect')) {
            $data['redirect'] = request()->input('redirect');
        }

        if (request()->ajax() || request()->isJson()) {
            // Return json response
            return response()->json(
                [
                    'success' => $success,
                    'data' => $data,
                ]
            );
        }

        // Return redirect response
        if (!empty($data['redirect'])) {
            return redirect()->to($data['redirect']);
        }

        // Return back with message
        $data['success'] = $success;
        $back = back()->withInput()->with($data);

        if (! $success) {
            // Return back with errors
            $back->withErrors([$data['message']]);
        }

        return $back;
    }

    /**
     * Response success message
     *
     * @param string|array $message
     * @return JsonResponse|RedirectResponse
     */
    protected function success(string|array $message): JsonResponse|RedirectResponse
    {
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        return $this->response($message);
    }

    /**
     * Response error message
     *
     * @param string|array $message
     * @return JsonResponse|RedirectResponse
     */
    protected function error(string|array $message): JsonResponse|RedirectResponse
    {
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        return $this->response($message, false);
    }
}
