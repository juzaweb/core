<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LarabizCMS\Core\Permissions\PermissionRegistrar;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ];

        $columnNames = [
            'role_pivot_key' => 'role_id',
            'permission_pivot_key' => 'permission_id',
            'model_morph_key' => 'model_id',
            'team_foreign_key' => 'team_id',
        ];

        $tbprefix = DB::getTablePrefix();

        Schema::create(
            $tableNames['permissions'],
            function (Blueprint $table) use ($tbprefix) {
                $table->bigIncrements('id');
                $table->string('code', 50)->index();
                $table->string('group', 50)->default('other');
                $table->string('name', 100);       // For MySQL 8.0 use string('name', 125);
                $table->string('guard_name', 100); // For MySQL 8.0 use string('guard_name', 125);
                $table->text('description')->nullable();
                $table->timestamps();

                $table->unique(['code', 'guard_name'], "{$tbprefix}permissions_code_guard_unique");
            }
        );

        Schema::create(
            $tableNames['roles'],
            function (Blueprint $table) use ($tbprefix) {
                $table->bigIncrements('id');
                $table->string('code', 50)->index();
                $table->string('name', 100);       // For MySQL 8.0 use string('name', 125);
                $table->string('guard_name', 100); // For MySQL 8.0 use string('guard_name', 125);
                $table->boolean('grant_all_permissions')->default(false);
                $table->timestamps();
                $table->unique(
                    ['code', 'guard_name'],
                    "{$tbprefix}roles_code_guard_unique"
                );
            }
        );

        Schema::create(
            $tableNames['model_has_permissions'],
            function (Blueprint $table) use (
                $tableNames,
                $columnNames,
                $tbprefix
            ) {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);

                $table->string('model_type');
                $table->uuid($columnNames['model_morph_key']);

                $table->index(
                    [$columnNames['model_morph_key'],
                        'model_type'],
                    "{$tbprefix}has_permissions_model_id_model_type_index"
                );

                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                $table->primary(
                    [PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    "{$tbprefix}has_permissions_permission_model_type_primary"
                );
            }
        );

        Schema::create(
            $tableNames['model_has_roles'],
            function (Blueprint $table) use (
                $tableNames,
                $columnNames,
                $tbprefix
            ) {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);

                $table->string('model_type');
                $table->uuid($columnNames['model_morph_key']);

                $table->index(
                    [$columnNames['model_morph_key'],
                        'model_type'],
                    $tbprefix . 'has_roles_model_id_model_type_index'
                );

                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');
                $table->primary(
                    [
                        PermissionRegistrar::$pivotRole,
                        $columnNames['model_morph_key'],
                        'model_type'
                    ],
                    $tbprefix . 'has_roles_role_model_type_primary'
                );
            }
        );

        Schema::create(
            $tableNames['role_has_permissions'],
            function (Blueprint $table) use ($tableNames, $tbprefix) {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);
                $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);

                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                $table->primary(
                    [
                        PermissionRegistrar::$pivotPermission,
                        PermissionRegistrar::$pivotRole
                    ],
                    $tbprefix . 'role_has_permissions_role_id_primary'
                );
            }
        );

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(cache_prefix('permissions'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $tableNames = [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ];

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};
