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
        $dish = strtolower($parts[1] ?? "");

        if ($command === "cuenta") {
            return "Total: 0.00";
        }

        if ($command === "añadir") {
            $price = $this->menu->getPrice($dish);
            $this->items[$dish] = 1;
            $totalFormatted = number_format($price, 2, '.', '');

            return "{$dish} x1 | Total: {$totalFormatted}";
        }

        return "";
    }
}
