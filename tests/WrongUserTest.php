<?php

namespace Tests;

class WrongUserTest extends TestCase
{
    public function testWrongUserAndPass()
    {
        $params = ['name' => 'bad', 'password' => 'incorrect'];
        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], -1);
        $this->assertEquals($response['response']['errorMessage'], "Authentication Failed");
    }

    public function testWrongUser()
    {
        $params = ['name' => 'bad', 'password' => 'password'];
        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], -1);
        $this->assertEquals($response['response']['errorMessage'], "Authentication Failed");
    }

    public function testWrongPassword()
    {
        $params = ['name' => 'admin', 'password' => 'incorrect'];
        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], -1);
        $this->assertEquals($response['response']['errorMessage'], "Authentication Failed");
    }

    public function testCorrectUserAndPass()
    {
        $params = ['name' => 'admin', 'password' => 'password'];
        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], 0);
    }
}
