<?php

class Order {
    private Menu $menu;
    public function __construct(Menu $menu) {
        $this->menu = $menu;
    }

    public function handle(string $instruction): string {
        $command = strtolower(trim($instruction));

        if ($command === "cuenta") {
            return "Total: 0.00";
        }

        return "";
    }
}
