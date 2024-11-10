<?php

namespace JacobTilly\LaravelDocsign\Models;

class Account
{
    public $email;
    public $balance;

    public function __construct(array $attributes)
    {
        $this->email = $attributes['email'] ?? null;
        $this->balance = $attributes['balance'] ?? null;
    }
}
