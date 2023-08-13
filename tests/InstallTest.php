<?php

class InstallTest extends TestCase
{
    public function testInstalledDirectories()
    {
        $this->assertFileExists(developer_path());

        $this->assertFileExists(developer_path('Controllers'));

        $this->assertFileExists(developer_path('routes.php'));

        $this->assertFileExists(developer_path('bootstrap.php'));

        $this->assertFileExists(developer_path('Controllers/HomeController.php'));

        $this->assertFileExists(developer_path('Controllers/AuthController.php'));

        $this->assertFileExists(developer_path('Controllers/ExampleController.php'));

        $this->assertFileExists(config_path('developer.php'));

        $this->assertFileExists(public_path('vendor/open-developer'));
    }
}
