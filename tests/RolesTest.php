<?php

use OpenDeveloper\Developer\Auth\Database\Administrator;
use OpenDeveloper\Developer\Auth\Database\Role;

class RolesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testRolesIndex()
    {
        $this->visit('developer/auth/roles')
            ->see('Roles')
            ->see('administrator');
    }

    public function testAddRole()
    {
        $this->visit('developer/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());
    }

    public function testAddRoleToUser()
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

        $this->assertEquals(1, Role::count());

        $this->visit('developer/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Administrator::find(2)->isRole('developer'));

        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.role_users_table'), ['user_id' => 2, 'role_id' => 2]);

        $this->assertTrue(Administrator::find(2)->isRole('developer'));

        $this->assertFalse(Administrator::find(2)->inRoles(['editor', 'operator']));
        $this->assertTrue(Administrator::find(2)->inRoles(['developer', 'operator', 'editor']));
    }

    public function testDeleteRole()
    {
        $this->assertEquals(1, Role::count());

        $this->visit('developer/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->delete('developer/auth/roles/2')
            ->assertEquals(1, Role::count());

        $this->delete('developer/auth/roles/1')
            ->assertEquals(0, Role::count());
    }

    public function testEditRole()
    {
        $this->visit('developer/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->visit('developer/auth/roles/2/edit')
            ->see('Roles')
            ->submitForm('Submit', ['name' => 'blablabla'])
            ->seePageIs('developer/auth/roles')
            ->seeInDatabase(config('developer.database.roles_table'), ['name' => 'blablabla'])
            ->assertEquals(2, Role::count());
    }
}
