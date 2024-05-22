<?php
require_once 'Slots.php';
require_once 'Machine.php';

$coins = (int)readline("Enter coins: ");
if ($coins < 5) {
    exit("Insufficient amount!");
}
$rows = (int)readline("Enter rows: ");
if ($rows < 3) {
    exit("Too small");
}
$columns = (int)readline("Enter columns: ");
if ($columns < $rows) {
    exit("Too small");
}
$elements = [
    new Slots("*", 5),
    new Slots("+", 15),
    new Slots("O", 35),
    new Slots("X", 60),
    new Slots("A", 100),
];
$slotMachine = new Machine($coins, $rows, $columns, $elements);
$slotMachine->play();
