<?php

namespace JacobTilly\LaravelDocsign\Models;

class Party
{
    public $id;
    public $name;
    public $company;
    public $email;
    public $phoneNumber;
    public $signMethod;
    public $externalId;

    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->company = $attributes['company'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->phoneNumber = $attributes['phone_number'] ?? null;
        $this->signMethod = $attributes['sign_method'] ?? null;
        $this->externalId = $attributes['external_id'] ?? null;
    }
}
