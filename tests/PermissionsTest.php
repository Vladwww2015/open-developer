<?php

use OpenDeveloper\Developer\Auth\Database\Administrator;
use OpenDeveloper\Developer\Auth\Database\Permission;
use OpenDeveloper\Developer\Auth\Database\Role;

class PermissionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testPermissionsIndex()
    {
        $this->assertTrue(Administrator::first()->isAdministrator());

        $this->visit('developer/auth/permissions')
            ->see('Permissions');
    }

    public function testAddAndDeletePermissions()
    {
        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('developer/auth/permissions')
            ->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => ['DELETE']])
            ->seePageIs('developer/auth/permissions')
            ->seeInDatabase(config('developer.database.permissions_table'), ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => 'GET'])
            ->seeInDatabase(config('developer.database.permissions_table'), ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => 'DELETE'])
            ->assertEquals(7, Permission::count());

        $this->assertTrue(Administrator::first()->can('can-edit'));
        $this->assertTrue(Administrator::first()->can('can-delete'));

        $this->delete('developer/auth/permissions/6')
            ->assertEquals(6, Permission::count());

        $this->delete('developer/auth/permissions/7')
            ->assertEquals(5, Permission::count());
    }

    public function testAddPermissionToRole()
    {
        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('developer/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('developer/auth/roles/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1]])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.role_permissions_table'), ['role_id' => 1, 'permission_id' => 1]);
    }

    public function testAddPermissionToUser()
    {
        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('developer/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('developer/auth/users/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1], 'roles' => [1]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.user_permissions_table'), ['user_id' => 1, 'permission_id' => 1])
            ->seeInDatabase(config('developer.database.role_users_table'), ['user_id' => 1, 'role_id' => 1]);
    }

    public function testAddUserAndAssignPermission()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('developer/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Administrator::find(2)->isAdministrator());

        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-update', 'name' => 'Can Update', 'http_path' => 'users/*/edit', 'http_method' => ['GET']])
            ->seePageIs('developer/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('developer/auth/permissions');

        $this->assertEquals(7, Permission::count());

        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Administrator::find(2)->can('can-update'));
        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));

        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [7]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Administrator::find(2)->can('can-remove'));

        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => []])
            ->seePageIs('developer/auth/users')
            ->missingFromDatabase(config('developer.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6])
            ->missingFromDatabase(config('developer.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Administrator::find(2)->cannot('can-update'));
        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));
    }

    public function testPermissionThroughRole()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // 1.add a user
        $this->visit('developer/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Administrator::find(2)->isAdministrator());

        // 2.add a role
        $this->visit('developer/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Administrator::find(2)->isRole('developer'));

        // 3.assign role to user
        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.role_users_table'), ['user_id' => 2, 'role_id' => 2]);

        $this->assertTrue(Administrator::find(2)->isRole('developer'));

        //  4.add a permission
        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('developer/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));

        // 5.assign permission to role
        $this->visit('developer/auth/roles/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.role_permissions_table'), ['role_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Administrator::find(2)->can('can-remove'));
    }

    public function testEditPermission()
    {
        $this->visit('developer/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('developer/auth/permissions')
            ->seeInDatabase(config('developer.database.permissions_table'), ['slug' => 'can-edit'])
            ->seeInDatabase(config('developer.database.permissions_table'), ['name' => 'Can edit'])
            ->assertEquals(6, Permission::count());

        $this->visit('developer/auth/permissions/1/edit')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete'])
            ->seePageIs('developer/auth/permissions')
            ->seeInDatabase(config('developer.database.permissions_table'), ['slug' => 'can-delete'])
            ->assertEquals(6, Permission::count());
    }
}
