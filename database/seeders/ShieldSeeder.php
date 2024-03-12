<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_curriculum","view_any_curriculum","create_curriculum","update_curriculum","restore_curriculum","restore_any_curriculum","replicate_curriculum","reorder_curriculum","delete_curriculum","delete_any_curriculum","force_delete_curriculum","force_delete_any_curriculum","view_customer","view_any_customer","create_customer","update_customer","restore_customer","restore_any_customer","replicate_customer","reorder_customer","delete_customer","delete_any_customer","force_delete_customer","force_delete_any_customer","view_education::class","view_any_education::class","create_education::class","update_education::class","restore_education::class","restore_any_education::class","replicate_education::class","reorder_education::class","delete_education::class","delete_any_education::class","force_delete_education::class","force_delete_any_education::class","view_education::level","view_any_education::level","create_education::level","update_education::level","restore_education::level","restore_any_education::level","replicate_education::level","reorder_education::level","delete_education::level","delete_any_education::level","force_delete_education::level","force_delete_any_education::level","view_education::subject","view_any_education::subject","create_education::subject","update_education::subject","restore_education::subject","restore_any_education::subject","replicate_education::subject","reorder_education::subject","delete_education::subject","delete_any_education::subject","force_delete_education::subject","force_delete_any_education::subject","view_machine","view_any_machine","create_machine","update_machine","restore_machine","restore_any_machine","replicate_machine","reorder_machine","delete_machine","delete_any_machine","force_delete_machine","force_delete_any_machine","view_order","view_any_order","create_order","update_order","restore_order","restore_any_order","replicate_order","reorder_order","delete_order","delete_any_order","force_delete_order","force_delete_any_order","view_paper","view_any_paper","create_paper","update_paper","restore_paper","restore_any_paper","replicate_paper","reorder_paper","delete_paper","delete_any_paper","force_delete_paper","force_delete_any_paper","view_product","view_any_product","create_product","update_product","restore_product","restore_any_product","replicate_product","reorder_product","delete_product","delete_any_product","force_delete_product","force_delete_any_product","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_semester","view_any_semester","create_semester","update_semester","restore_semester","restore_any_semester","replicate_semester","reorder_semester","delete_semester","delete_any_semester","force_delete_semester","force_delete_any_semester","view_type","view_any_type","create_type","update_type","restore_type","restore_any_type","replicate_type","reorder_type","delete_type","delete_any_type","force_delete_type","force_delete_any_type","page_MyProfilePage","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"operator","guard_name":"web","permissions":["page_MyProfilePage"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (!blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
