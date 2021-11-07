<?php

namespace Tests;

class PerfectTest extends TestCase
{
    const POS_ID = 99;

    public function testStartOperation()
    {
        $params = [
            'name' => 'admin',
            'password' => 'password',
            'operation' => 'startOperation',
            'amount' => 15,
            'pos_id' => self::POS_ID,
        ];
        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], 1);
        $this->assertEquals($response['response']['errorMessage'], 'none');
        $this->assertIsInt($response['response']['operation']['operationId']);

        return $response['response']['operation']['operationId'];
    }

    /**
     * @depends testStartOperation
     */
    public function testAcknowledgeOperationId($operationId)
    {
        $params = [
            'name' => 'admin',
            'password' => 'password',
            'operation' => 'acknowledgeOperationId',
            'operationId' => $operationId,
            'pos_id' => self::POS_ID,
        ];

        $response = $this->loadUrl('http://local.cashdro.com/index3.php', $params);

        $this->assertEquals($response['code'], 1);
        $this->assertEquals($response['response']['errorMessage'], 'none');

        return $operationId;
    }

    /**
     * @depends testAcknowledgeOperationId
     * @param $operationId
     */
    public function testAskOperation($operationId)
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @depends  testStartOperation
     */
    public function testFinishOperation($operationId)
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @depends testFinishOperation
     */
    public function testSetOperationImported($operationId)
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
