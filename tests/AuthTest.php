<?php

class AuthTest extends TestCase
{
    public function testLoginPage()
    {
        $this->visit('developer/auth/login')
            ->see('login');
    }

    public function testVisitWithoutLogin()
    {
        $this->visit('developer')
            ->dontSeeIsAuthenticated('developer')
            ->seePageIs('developer/auth/login');
    }

    public function testLogin()
    {
        $credentials = ['username' => 'developer', 'password' => 'developer'];

        $this->visit('developer/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'developer')
            ->seeIsAuthenticated('developer')
            ->seePageIs('developer')
            ->see('Dashboard')
            ->see('Description...')

            ->see('Environment')
            ->see('PHP version')
            ->see('Laravel version')

            ->see('Available extensions')
            ->seeLink('open-admin-ext/helpers', 'https://github.com/open-admin-extensions/helpers')
            ->seeLink('open-admin-ext/backup', 'https://github.com/open-admin-extensions/backup')
            ->seeLink('open-admin-ext/media-manager', 'https://github.com/open-admin-extensions/media-manager')

            ->see('Dependencies')
            ->see('php')
//            ->see('>=7.0.0')
            ->see('laravel/framework');

        $this
            ->see('<span>Developer</span>')
            ->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testLogout()
    {
        $this->visit('developer/auth/logout')
            ->seePageIs('developer/auth/login')
            ->dontSeeIsAuthenticated('developer');
    }
}
