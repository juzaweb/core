<?php

namespace Juzaweb\Core\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HasSessionResponses
{
    protected function response(array|string $data, string $status): JsonResponse|RedirectResponse
    {
        if (! is_array($data)) {
            $data = ['message' => $data];
        }

        if (request()->has('redirect')) {
            $data['redirect'] = request()->input('redirect');
        }

        if (request()->ajax() || request()->isJson()) {
            return response()->json(
                [
                    'status' => $status,
                    'data' => $data,
                ]
            );
        }

        if (!empty($data['redirect'])) {
            return redirect()->to($data['redirect']);
        }

        $data['status'] = $status;
        $back = back()->withInput()->with($data);

        if ($status === 'error') {
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

        return $this->response($message, 'success');
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

        return $this->response($message, 'error');
    }
}
