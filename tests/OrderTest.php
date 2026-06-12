<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Order.php';
require_once __DIR__ . '/../src/Menu.php';

class OrderTest extends TestCase {
    private Order $order;
    private Menu $menuMock;
    protected function setUp(): void {
        // Arrange
        $this->menuMock = $this->createMock(Menu::class);
        $this->order = new Order($this->menuMock);
    }

    public function test_bill_returns_zero_total_when_order_is_empty(): void {
        // Act
        $result = $this->order->handle("cuenta");

        // Assert
        $this->assertEquals("Total: 0.00", $result);
    }

    public function test_add_existing_dish__without_quantity_returns_order_with_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->with("pizza")->willReturn(10.00);
        
        // Act
        $result = $this->order->handle("añadir pizza");

        // Assert
        $this->assertEquals("pizza x1 | Total: 10.00", $result);
    }
}
