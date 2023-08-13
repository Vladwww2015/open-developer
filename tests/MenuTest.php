<?php

use OpenDeveloper\Developer\Auth\Database\Administrator;
use OpenDeveloper\Developer\Auth\Database\Menu;

class MenuTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testMenuIndex()
    {
        $this->visit('developer/auth/menu')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu');
    }

    public function testAddMenu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $this->visit('developer/auth/menu')
            ->seePageIs('developer/auth/menu')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('developer/auth/menu')
            ->seeInDatabase(config('developer.database.menu_table'), $item)
            ->assertEquals(8, Menu::count());

//        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);
//
//        $this->visit('developer')
//            ->see('Test')
//            ->click('Test');
    }

    public function testDeleteMenu()
    {
        $this->delete('developer/auth/menu/8')
            ->assertEquals(7, Menu::count());
    }

    public function testEditMenu()
    {
        $this->visit('developer/auth/menu/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('developer/auth/menu')
            ->seeInDatabase(config('developer.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(7, Menu::count());
    }

    public function testShowPage()
    {
        $this->visit('developer/auth/menu/1/edit')
            ->seePageIs('developer/auth/menu/1/edit');
    }

    public function testEditMenuParent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('developer/auth/menu/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
