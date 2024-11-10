<?php

namespace JacobTilly\LaravelDocsign\Models;

class SignedParty extends Party
{
    public $signedAt;
    public $signedByName;
    public $signedById;
    public $signUrl;

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
        $this->signedAt = $attributes['signed_at'] ?? null;
        $this->signedByName = $attributes['signed_by_name'] ?? null;
        $this->signedById = $attributes['signed_by_id'] ?? null;
        $this->signUrl = $attributes['sign_url'] ?? null;
    }
}
