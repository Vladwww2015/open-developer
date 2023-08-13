<?php

use OpenAdmin\Admin\Auth\Database\Administrator;

class UsersTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = Administrator::first();

        $this->be($this->user, 'developer');
    }

    public function testUsersIndexPage()
    {
        $this->visit('developer/auth/users')
            ->see('Administrator');
    }

    public function testCreateUser()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // create user
        $this->visit('developer/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.users_table'), ['username' => 'Test']);

        // assign role to user
        $this->visit('developer/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [1]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.role_users_table'), ['user_id' => 2, 'role_id' => 1]);

        $this->visit('developer/auth/logout')
            ->dontSeeIsAuthenticated('developer')
            ->seePageIs('developer/auth/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('developer')
            ->seePageIs('developer');

        $this->assertTrue($this->app['auth']->guard('developer')->getUser()->isAdministrator());

        $this->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('developer/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test', 'roles' => [1]])
            ->seePageIs('developer/auth/users')
            ->seeInDatabase(config('developer.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $this->visit('developer/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', $data)
            ->seePageIs('developer/auth/users')
            ->visit('developer/auth/logout')
            ->dontSeeIsAuthenticated('developer')
            ->seePageIs('developer/auth/login')
            ->submitForm('Login', ['username' => $this->user->username, 'password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('developer')
            ->seePageIs('developer');
    }
}
