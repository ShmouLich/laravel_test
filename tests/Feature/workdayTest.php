<?php

namespace Tests\Feature;

use Tests\TestCase;

class workdayTest extends TestCase {
    public function testHolidaysNoWeekend(): void
    {
        $response = $this->post(config('http-client.host') . '/trvani', [
        'startDate' => '2024-05-07',
        'durationMinutes' => '960',
        'considerHolidays' => true,
        'workTimeStart' => '08:00:00',
        'workTimeEnd' => '16:00:00',
        ]);

        $responseData = $response->json();

        $response->assertStatus(200);

        $this->assertArrayHasKey('completionDate', $responseData);

        $completionDate = substr($responseData['completionDate'], 0, 10);

        $this->assertEquals('2024-05-10', $completionDate);
    }

    public function testHolidaysWithWeekend(): void
    {
        $response = $this->post(config('http-client.host') . '/trvani', [
            'startDate' => '2024-05-07',
            'durationMinutes' => '961',
            'considerHolidays' => true,
            'workTimeStart' => '08:00:00',
            'workTimeEnd' => '16:00:00',
        ]);

        $responseData = $response->json();

        $response->assertStatus(200);

        $this->assertArrayHasKey('completionDate', $responseData);

        $completionDate = substr($responseData['completionDate'], 0, 10);

        $this->assertEquals('2024-05-13', $completionDate);
    }

    public function testNoHolidaysNoWeekend(): void
    {
        $response = $this->post(config('http-client.host') . '/trvani', [
            'startDate' => '2024-05-07',
            'durationMinutes' => '960',
            'considerHolidays' => false,
            'workTimeStart' => '08:00:00',
            'workTimeEnd' => '16:00:00',
        ]);

        $responseData = $response->json();

        $response->assertStatus(200);

        $this->assertArrayHasKey('completionDate', $responseData);

        $completionDate = substr($responseData['completionDate'], 0, 10);

        $this->assertEquals('2024-05-09', $completionDate);
    }

}

