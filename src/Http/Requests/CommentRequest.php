<?php

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Admin\Models\Guest;
use Juzaweb\Modules\Core\Enums\CommentStatus;
use Juzaweb\Modules\Core\Models\Model;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'max:500'],
            'name' => [Rule::requiredIf(auth()->guest()), 'max:100'],
            'email' => [Rule::requiredIf(auth()->guest()), 'email:rfc', 'max:190'],
        ];
    }

    public function save(Model $model)
    {
        $user = $this->user() ?? Guest::updateOrCreate(
            [
                'ipv4' => client_ip(),
            ],
            [
                'user_agent' => $this->userAgent(),
                'name' => $this->input('name'),
                'email' => $this->input('email'),
            ]
        );

        return DB::transaction(
            function () use ($model, $user) {
                $comment = $model->comments()->make([
                    'content' => $this->input('content'),
                    'status' => CommentStatus::APPROVED,
                ]);

                $comment->commentable()->associate($model);
                $comment->commented()->associate($user);
                $comment->save();

                if ($user instanceof Guest) {
                    Cookie::queue(
                        'guest_name',
                        $user->name,
                        60 * 24 * 30 // 30 days
                    );

                    Cookie::queue(
                        'guest_email',
                        $user->email,
                        60 * 24 * 30 // 30 days
                    );
                }

                return $comment;
            }
        );
    }
}
