<?php

namespace models;

class Task extends Model
{
    protected string $table = 'tasks';

    /**
     * check if task text is changed
     *
     * @param string $text
     * @return bool
     */
    public function isTextChanged($text): bool
    {
        return $this->getAttribute('text') != $text;
    }

    /**
     * check if task is completed
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return (bool)$this->getAttribute('completed');
    }
}