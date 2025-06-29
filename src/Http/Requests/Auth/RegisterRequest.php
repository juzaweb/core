<?php
namespace Juzaweb\Core\Http\Requests\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Juzaweb\Core\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="RegisterRequest",
 *      required=true,
 *      @OA\JsonContent(
 *          required={"email", "password", "name", "password_confirmation"},
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="email", type="string", format="email"),
 *          @OA\Property(property="password", type="string"),
 *          @OA\Property(property="password_confirmation", type="string"),
 *          @OA\Property(property="birthday", type="string", format="date"),
 *      )
 * )
 */
class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function register(): User
    {
        $verifyEmail = setting('user_verification');

        $data = $this->safe()->merge([
            'password' => Hash::make($this->post('password')),
        ])->all();

        /** @var User $user */
        $user = DB::transaction(
            function () use ($data, $verifyEmail) {
                $user = new User();
                $user->fill($data);

                if (! $verifyEmail) {
                    $user->forceFill(['email_verified_at' => now()]);
                }

                $user->save();

                return $user;
            }
        );

        event(new Registered($user));

        return $user;
    }
}
