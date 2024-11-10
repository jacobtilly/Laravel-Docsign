<?php

namespace JacobTilly\LaravelDocsign\Models;

class Document
{
    public $id;
    public $name;
    public $state;
    public $comment;
    public $createdAt;
    public $originalPdfUrl;
    public $signedPdfUrl;
    public $parties;

    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->state = $attributes['state'] ?? null;
        $this->comment = $attributes['comment'] ?? null;
        $this->createdAt = $attributes['created_at'] ?? null;
        $this->originalPdfUrl = $attributes['original_pdf_url'] ?? null;
        $this->signedPdfUrl = $attributes['signed_pdf_url'] ?? null;

        $this->parties = array_map(function ($partyAttributes) {
            return is_array($partyAttributes) ? new SignedParty($partyAttributes) : $partyAttributes;
        }, $attributes['parties'] ?? []);
    }
}
