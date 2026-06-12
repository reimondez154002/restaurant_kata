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

    public function test_add_existing_dish__with_quantity_returns_order_with_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->with("pizza")->willReturn(10.00);
        
        // Act
        $result = $this->order->handle("añadir pizza 2");

        // Assert
        $this->assertEquals("pizza x2 | Total: 20.00", $result);
    }

    public function test_add_non_existing_dish_returns_error_message(): void {
        // Arrange
        $this->menuMock->method("getPrice")->with("chistorra")->willReturn(null);

        // Act
        $result = $this->order->handle("añadir chistorra");

        // Assert
        $this->assertEquals("El plato seleccionado no existe en el menú", $result);
    }
    
    public function test_add_same_dish_multiple_times_accumulates_quantity_and_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->with("pizza")->willReturn(10.00);

        // Act
        $this->order->handle("añadir pizza"); 
        $result = $this->order->handle("añadir Pizza 2");

        // Assert
        $this->assertEquals("pizza x3 | Total: 30.00", $result);
    }

    public function test_add_multiple_different_dishes_returns_right_alphabetically_sorted_comanda_with_global_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->willReturnCallback(function($dish) {
            if ($dish === "pizza") return 10.00;
            if ($dish === "agua") return 3.00;
            return null;
        });

        // Act
        $this->order->handle("añadir pizza 2");
        $result = $this->order->handle("añadir agua 1");

        // Assert
        $this->assertEquals("agua x1, pizza x2 | Total: 23.00", $result);
    }
    public function test_bill_returns_calculated_total_of_all_dishes_in_order(): void {
        // Arrange
        $this->menuMock->method("getPrice")->willReturnCallback(function($dish) {
            if ($dish === "pizza") return 10.00;
            if ($dish === "agua") return 3.00;
            return null;
        });

        // Act
        $this->order->handle("añadir pizza");
        $this->order->handle("añadir agua");
        $result = $this->order->handle("cuenta");

        // Assert
        $this->assertEquals("Total: 13.00", $result);
    }

    public function test_eliminar_command_removes_dish_completely_and_returns_remaining_items_without_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->willReturnCallback(function($dish) {
            if ($dish === "pizza") return 10.00;
            if ($dish === "agua") return 3.00;
            return null;
        });

        // Act
        $this->order->handle("añadir pizza 2");
        $this->order->handle("añadir agua 1");
        $result = $this->order->handle("eliminar pizza");

        // Assert
        // Al eliminar la pizza, solo debe quedar el agua y sin la parte de " | Total: XX"
        $this->assertEquals("agua x1", $result);
    }

    public function test_eliminar_command_if_dish_doesnt_exists_in_order_returns_remaining_items_without_total(): void {
        // Arrange
        $this->menuMock->method("getPrice")->willReturnCallback(function($dish) {
            if ($dish === "pizza") return 10.00;
            if ($dish === "agua") return 3.00;
            return null;
        });

        // Act
        $this->order->handle("añadir pizza 2");
        $this->order->handle("añadir agua 1");
        $result = $this->order->handle("eliminar chistorra");

        // Assert
        // Al eliminar la pizza, solo debe quedar el agua y sin la parte de " | Total: XX"
        $this->assertEquals("El plato seleccionado no existe", $result);
    }
}
