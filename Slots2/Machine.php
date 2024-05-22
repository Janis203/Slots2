<?php
require_once 'Slots.php';

class Machine
{
    private int $coins;
    private int $rows;
    private int $columns;
    private array $elements;
    private array $field = [];

    public function __construct($coins, $rows, $columns, $elements)
    {
        $this->coins = $coins;
        $this->rows = $rows;
        $this->columns = $columns;
        $this->elements = $elements;
    }

    private function getRandom(): string
    {
        $random = rand(1, 100);
        if ($random <= $this->elements[0]->getChance()) {
            return $this->elements[0]->getElement();
        } elseif ($random <= $this->elements[1]->getChance()) {
            return $this->elements[1]->getElement();
        } elseif ($random <= $this->elements[2]->getChance()) {
            return $this->elements[2]->getElement();
        } elseif ($random <= $this->elements[3]->getChance()) {
            return $this->elements[3]->getElement();
        } else {
            return $this->elements[4]->getElement();
        }
    }

    private function spin()
    {
        $this->field = [];
        echo str_repeat("___", $this->columns) . PHP_EOL;
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->columns; $j++) {
                $this->field[$i][$j] = $this->getRandom();
                echo "|" . $this->field[$i][$j] . "|";
            }
            echo "\n" . str_repeat("---", $this->columns) . PHP_EOL;
        }
    }

    private function checkRows(): array
    {
        $winSymbols = [];
        for ($i = 0; $i < $this->rows; $i++) {
            $combo = 0;
            for ($j = 1; $j < $this->columns; $j++) {
                if ($this->field[$i][$j] === $this->field[$i][$j - 1]) {
                    $combo++;
                }
            }
            if ($combo === $this->columns - 1) {
                $winSymbols[] = $this->field[$i][0];
            }
        }
        return $winSymbols;
    }

    private function checkDiagonals(): array
    {
        $winSymbols = [];
        $combo = 0;
        $rowNr = 1;
        $previousSymbol = $this->field[0][0];
        for ($i = 1; $i < $this->columns; $i++) {
            if ($this->field[$rowNr][$i] !== $previousSymbol) {
                break;
            }
            $combo++;
            if ($rowNr < $this->rows - 1) {
                $rowNr++;
            }
        }
        if ($combo === $this->columns - 1) {
            $winSymbols[] = $this->field[0][0];
        }
        $combo = 0;
        $rowNr = $this->rows - 1;
        $previousSymbol = $this->field[$this->rows - 1][0];
        for ($i = 1; $i < $this->columns; $i++) {
            if ($this->field[$rowNr][$i] !== $previousSymbol) {
                break;
            }
            $combo++;
            if ($rowNr > 0) {
                $rowNr--;
            }
        }
        if ($combo === $this->columns - 1) {
            $winSymbols[] = $this->field[$this->rows - 1][0];
        }
        return $winSymbols;
    }

    private function checkWins(int $betAmount)
    {
        $winSymbols = $this->checkRows();
        $winSymbols = array_merge($winSymbols, $this->checkDiagonals());
        if (count($winSymbols) > 0) {
            foreach ($winSymbols as $symbol) {
                switch ($symbol) {
                    case "*":
                        echo "You won " . (($betAmount + 5) * 5) . " coins!" . PHP_EOL;
                        $this->coins += ($betAmount + 5) * 5;
                        break;
                    case "+":
                        echo "You won " . (($betAmount + 4) * 3) . " coins!" . PHP_EOL;
                        $this->coins += ($betAmount + 4) * 3;
                        break;
                    case "O":
                        echo "You won " . (($betAmount + 3) * 2) . " coins!" . PHP_EOL;
                        $this->coins += ($betAmount + 3) * 2;
                        break;
                    case "X":
                        echo "You won " . ($betAmount * 2) . " coins!" . PHP_EOL;
                        $this->coins += $betAmount * 2;
                        break;
                    case "A":
                        echo "You won $betAmount coins!" . PHP_EOL;
                        $this->coins += $betAmount;
                        break;
                }
            }
        } else {
            $this->coins -= $betAmount;
            echo "You lost! $this->coins coins left" . PHP_EOL;
        }
    }

    public function play()
    {
        while (true) {
            $betAmount = (int)readline("Enter bet: ");
            if ($betAmount < 5 || $betAmount > $this->coins) {
                echo "Wrong bet amount" . PHP_EOL;
                continue;
            }
            $this->spin();
            $this->checkWins($betAmount);
            if ($this->coins < 5) {
                exit ("Insufficient amount. You have $this->coins coins");
            }
            $userChoice = strtolower(readline("Stop?(yes/y) "));
            if ($userChoice === "yes" || $userChoice === "y") {
                exit("You got $this->coins coins!");
            } else {
                echo "You have $this->coins left" . PHP_EOL;
            }
        }
    }
}