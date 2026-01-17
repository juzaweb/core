<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Juzaweb\Modules\Admin\Models\User;
use Symfony\Component\Console\Input\InputOption;

class MakeUserCommand extends Command
{
    protected $name = 'user:make';

    protected array $user;

    public function handle(): void
    {
        $this->user['name'] = $this->option('name') ?? $this->ask('Full Name?');
        $this->user['email'] = $this->option('email') ?? $this->ask('Email?');
        $this->user['password'] = $this->option('pass') ?? $this->ask('Password?');

        $validator = Validator::make(
            $this->user,
            [
                'name' => 'required|max:150',
                'email' => 'required|email|max:150',
                'password' => 'required|max:32|min:6',
            ],
            [],
            [
                'name' => trans('core::translation.name'),
                'email' => trans('core::translation.email'),
                'password' => trans('core::translation.password'),
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->messages()[0]);
            exit(1);
        }

        $user = DB::transaction(
            function () use ($validator) {
                $user = User::create($validator->safe()->merge(
                    [
                        'password' => Hash::make($this->user['password']),
                        'is_super_admin' => $this->option('super-admin'),
                        'email_verified_at' => now(),
                    ]
                )->all());

                if ($this->option('role')) {
                    $user->assignRole($this->option('role'));
                }

                return $user;
            }
        );

        $this->info("User created successfully user {$user->name}");
    }

    protected function getOptions(): array
    {
        return [
            ['super-admin', null, InputOption::VALUE_NONE, 'Create super admin user'],
            ['role', null, InputOption::VALUE_OPTIONAL, 'Assign role to user'],
            ['name', null, InputOption::VALUE_OPTIONAL, 'The name of the admin user.'],
            ['email', null, InputOption::VALUE_OPTIONAL, 'The email of the admin user.'],
            ['pass', null, InputOption::VALUE_OPTIONAL, 'The password of the admin user.'],
        ];
    }
}
