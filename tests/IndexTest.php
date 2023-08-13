<?php

use OpenAdmin\Admin\Auth\Database\Administrator;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testIndex()
    {
        $this->visit('developer/')
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
    }

    public function testClickMenu()
    {
        $this->visit('developer/')
            ->click('Users')
            ->seePageis('developer/auth/users')
            ->click('Roles')
            ->seePageis('developer/auth/roles')
            ->click('Permission')
            ->seePageis('developer/auth/permissions')
            ->click('Menu')
            ->seePageis('developer/auth/menu')
            ->click('Operation log')
            ->seePageis('developer/auth/logs');
    }
}
