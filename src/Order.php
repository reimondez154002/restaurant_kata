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
            return $this->checkReturn();
        }

        if ($command === "añadir") {

            return $this->addItem($parts);
        }

        if($command === "eliminar"){
            
            return $this->removeItem($parts);
        }

        return "";
    }

    private function removeItem(array $parts): string {
        $dish = strtolower($parts[1] ?? "");

        if(!isset($this->items[$dish])){
            return "El plato seleccionado no existe";
        }
        // Eliminamos directamente el plato de la comanda
        unset($this->items[$dish]);

        foreach ($this->items as $currentDish => $currentQuantity) {
            $orderLines[] = "{$currentDish} x{$currentQuantity}";
        }
        $comandaState = implode(", ", $orderLines);

        return "{$comandaState}";
    }

    private function checkReturn(): string {
        $total = $this->calculateTotal();
        $totalFormatted = number_format($total, 2, '.', '');

        return "Total: {$totalFormatted}";
    }

    private function addItem(array $parts): string {
        $dish = strtolower($parts[1] ?? "");
        $quantity = isset($parts[2]) ? (int)$parts[2] : 1;

        $price = $this->menu->getPrice($dish);

        if ($price === null) {
            return "El plato seleccionado no existe en el menú";
        }

        if (!isset($this->items[$dish])) {
            $this->items[$dish] = 0;
        }

        $this->items[$dish] += $quantity;

        uksort($this->items, 'strnatcmp');
        // Construimos la lista en el orden en que se van insertando (sin ordenar alfabéticamente)
        $orderLines = [];
        foreach ($this->items as $currentDish => $currentQuantity) {
            $orderLines[] = "{$currentDish} x{$currentQuantity}";
        }
        $comandaState = implode(", ", $orderLines);

        $totalGlobal = $this->calculateTotal();
        $totalFormatted = number_format($totalGlobal, 2, '.', '');

        return "{$comandaState} | Total: {$totalFormatted}";
    }

    private function calculateTotal(): float {
        $total = 0.0;

        foreach ($this->items as $dish => $quantity) {
            $price = $this->menu->getPrice($dish);
            $total += $price * $quantity;
        }

        return $total;
    }
}