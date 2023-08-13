<?php

use OpenDeveloper\Developer\Auth\Database\Administrator;
use OpenDeveloper\Developer\Auth\Database\OperationLog;

class OperationLogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'developer');
    }

    public function testOperationLogIndex()
    {
        $this->visit('developer/auth/logs')
            ->see('Operation log')
            ->see('List')
            ->see('GET')
            ->see('developer/auth/logs');
    }

    public function testGenerateLogs()
    {
        $table = config('developer.database.operation_log_table');

        $this->visit('developer/auth/menu')
            ->seePageIs('developer/auth/menu')
            ->visit('developer/auth/users')
            ->seePageIs('developer/auth/users')
            ->visit('developer/auth/permissions')
            ->seePageIs('developer/auth/permissions')
            ->visit('developer/auth/roles')
            ->seePageIs('developer/auth/roles')
            ->visit('developer/auth/logs')
            ->seePageIs('developer/auth/logs')
            ->seeInDatabase($table, ['path' => 'developer/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/roles', 'method' => 'GET']);

        $this->assertEquals(4, OperationLog::count());
    }

    public function testDeleteLogs()
    {
        $table = config('developer.database.operation_log_table');

        $this->visit('developer/auth/logs')
            ->seePageIs('developer/auth/logs')
            ->assertEquals(0, OperationLog::count());

        $this->visit('developer/auth/users');

        $this->seeInDatabase($table, ['path' => 'developer/auth/users', 'method' => 'GET']);

        $this->delete('developer/auth/logs/1')
            ->assertEquals(0, OperationLog::count());
    }

    public function testDeleteMultipleLogs()
    {
        $table = config('developer.database.operation_log_table');

        $this->visit('developer/auth/menu')
            ->visit('developer/auth/users')
            ->visit('developer/auth/permissions')
            ->visit('developer/auth/roles')
            ->seeInDatabase($table, ['path' => 'developer/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'developer/auth/roles', 'method' => 'GET'])
            ->assertEquals(4, OperationLog::count());

        $this->delete('developer/auth/logs/1,2,3,4')
            ->notSeeInDatabase($table, ['path' => 'developer/auth/menu', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'developer/auth/users', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'developer/auth/permissions', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'developer/auth/roles', 'method' => 'GET'])

            ->assertEquals(0, OperationLog::count());
    }
}
