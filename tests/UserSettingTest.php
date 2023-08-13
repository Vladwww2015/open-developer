<?php

use Illuminate\Support\Facades\File;
use OpenAdmin\Admin\Auth\Database\Administrator;

class UserSettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testVisitSettingPage()
    {
        $this->visit('developer/auth/setting')
            ->see('User setting')
            ->see('Username')
            ->see('Name')
            ->see('Avatar')
            ->see('Password')
            ->see('Password confirmation');

        $this->seeElement('input[value=Administrator]')
            ->seeInElement('.box-body', 'administrator');
    }

    public function testUpdateName()
    {
        $data = [
            'name' => 'tester',
        ];

        $this->visit('developer/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('developer/auth/setting');

        $this->seeInDatabase('developer_users', ['name' => $data['name']]);
    }

    public function testUpdateAvatar()
    {
        File::cleanDirectory(public_path('uploads/images'));

        $this->visit('developer/auth/setting')
            ->attach(__DIR__.'/assets/test.jpg', 'avatar')
            ->press('Submit')
            ->seePageIs('developer/auth/setting');

        $avatar = Administrator::first()->avatar;

        $this->assertEquals('http://localhost:8000/uploads/images/test.jpg', $avatar);
    }

    public function testUpdatePasswordConfirmation()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123',
        ];

        $this->visit('developer/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('developer/auth/setting')
            ->see('The Password confirmation does not match.');
    }

    public function testUpdatePassword()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('developer/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('developer/auth/setting');

        $this->assertTrue(app('hash')->check($data['password'], Administrator::first()->makeVisible('password')->password));

        $this->visit('developer/auth/logout')
            ->seePageIs('developer/auth/login')
            ->dontSeeIsAuthenticated('developer');

        $credentials = ['username' => 'developer', 'password' => '123456'];

        $this->visit('developer/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'developer')
            ->seeIsAuthenticated('developer')
            ->seePageIs('developer');
    }
}
