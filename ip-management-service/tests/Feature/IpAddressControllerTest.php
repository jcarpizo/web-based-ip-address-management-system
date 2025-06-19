<?php

namespace Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IpAddressControllerTest extends TestCase
{
    private const API_KEY = '3qohBUb4RJLUduNQ2ArCrokddmtmckl42vZ1g0IN';

    private array $headers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->headers = ['X-API-KEY' => self::API_KEY];
    }

    /**
     * @return void
     */
    public function test_create_ip_address_failed()
    {
        $response = $this->post(route('ipCreate'),[], $this->headers);
        $this->assertStringContainsString('The ip address field is required', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_create_ip_address()
    {
        $response = $this->createIpAddressWithIpv4();
        $this->assertStringContainsString('IP Address successfully created', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    /**
     * @throws RandomException
     */
    public function test_update_ip_address()
    {
        $response = $this->put(route('ipUpdate',['id' => $this->getIdIpAddressWithIpv4()]),$this->payload(), $this->headers);
        $this->assertStringContainsString('IP Address successfully updated', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_get_ip_address()
    {
        $response = $this->get(route('ipGet',['id' => $this->getIdIpAddressWithIpv4()]), $this->headers);
        $this->assertStringContainsString('IP Addresses successfully retrieved', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_get_ip_delete()
    {
        $response = $this->delete(route('ipDelete',['id' => $this->getIdIpAddressWithIpv4()]),[],$this->headers);
        $this->assertStringContainsString('IP Address successfully deleted', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    public function test_get_all_ip_logs()
    {
        $response = $this->get(route('ipLogList'),$this->headers);
        $this->assertStringContainsString('IP Addresses Logs successfully retrieved"', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    /**
     * @return TestResponse
     */
    private function createIpAddressWithIpv4(): TestResponse
    {
        return $this->post(route('ipCreate'), $this->payload([
            'comments' => 'This from PHP Unit Test',
        ]), $this->headers);
    }

    /**
     * @return int
     */
    private function getIdIpAddressWithIpv4(): int
    {
        $createdIpAddress = $this->createIpAddressWithIpv4();
        return json_decode($createdIpAddress->content())->ip_address->id;
    }

    /**
     * @throws RandomException
     */
    public function randomIpv4(): string
    {
        $num = random_int(0, 0xFFFFFFFF);
        return long2ip($num);
    }

    /**
     * @throws RandomException
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'label' => 'test one',
            'ip_address' => $this->randomIpv4(),
            'added_by_user_id' => 1,
            'updated_by_user_id' => 1,
        ], $overrides);
    }
}
