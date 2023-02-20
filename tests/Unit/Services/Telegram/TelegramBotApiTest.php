<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    public function testSendMessageSuccess(): void
    {
        Http::fake([
            TelegramBotApi::HOST.'*' => Http::response(['ok' => true]),
        ]);

        $result = TelegramBotApi::sendMessage('', 1, 'Test message');

        $this->assertTrue($result);
    }
}