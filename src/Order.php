<?php

class Order {
    private Menu $menu;
    private array $items = [];

    public function __construct(Menu $menu) {
        $this->menu = $menu;
    }

    public function handle(string $instruction): string {
        $parts = explode(" ", trim($instruction));
        $command = strtolower($parts[0] ?? "");


        if ($command === "cuenta") {

            return "Total: 0.00";
        }

        if ($command === "añadir") {

            return $this->addItem($parts);
        }

        return "";
    }
     private function addItem(array $parts):string{

        $dish = strtolower($parts[1] ?? "");
        $quantity = isset($parts[2]) ? (int)$parts[2] : 1;

        $price = $this->menu->getPrice($dish);

        if ($price === null) {
            return "El plato seleccionado no existe en el menú";
        }

        $this->items[$dish] = $quantity;
        $totalPrice = $price * $quantity;
        $totalFormatted = number_format($totalPrice, 2, '.', '');

        return "{$dish} x{$quantity} | Total: {$totalFormatted}";
     }
}
