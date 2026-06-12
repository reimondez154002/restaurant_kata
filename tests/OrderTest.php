<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Order.php';
require_once __DIR__ . '/../src/Menu.php';

class OrderTest extends TestCase {
    private Order $order;

    protected function setUp(): void {
        // Arrange
        $menuMock = $this->createMock(Menu::class);
        $this->order = new Order($menuMock);
    }

    public function test_bill_returns_zero_total_when_order_is_empty(): void {
        // Act
        $result = $this->order->handle("cuenta");

        // Assert
        $this->assertEquals("Total: 0.00", $result);
    }
}