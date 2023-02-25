<?php

namespace Tests\Unit\Support\ValueObjects;

use Support\ValueObjects\Price;

class PriceTest extends \Tests\TestCase
{
    public function testAll()
    {
        $price = Price::make(10000);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(100, $price->value());
        $this->assertEquals(10000, $price->raw());
        $this->assertEquals('RUB', $price->currency());
        $this->assertEquals('₽', $price->symbol());
        $this->assertEquals('10 000,00 ₽', (string) $price);

        $this->expectException(\InvalidArgumentException::class);
        Price::make(-10000);
        Price::make(10000, 'NONE');
    }
}