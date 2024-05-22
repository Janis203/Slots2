<?php

class Slots
{
    private string $element;
    private int $chance;

    public function __construct(string $element, int $chance)
    {
        $this->element = $element;
        $this->chance = $chance;
    }

    public function getElement(): string
    {
        return $this->element;
    }

    public function getChance(): int
    {
        return $this->chance;
    }
}