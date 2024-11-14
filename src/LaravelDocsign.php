<?php

namespace JacobTilly\LaravelDocsign;

use Illuminate\Support\Facades\Http;
use JacobTilly\LaravelDocsign\Exceptions\DocsignException;
use JacobTilly\LaravelDocsign\Models\Account;
use JacobTilly\LaravelDocsign\Models\Document;
use JacobTilly\LaravelDocsign\Models\Party;
use JacobTilly\LaravelDocsign\Models\SignedParty;

class LaravelDocsign
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('docsign.api_key');
    }

    protected function handleResponse($response, $relatedDocument = null)
    {
        if ($response->status() == 404) {
            throw new DocsignException("Document ID is invalid.");
        }

        $responseData = $response->json();

        if (isset($responseData['success'])) {
            if ($responseData['success']) {
                return [
                    'apiresponse' => $responseData,
                    'document' => $relatedDocument ? $this->getDocument($relatedDocument) : null,
                ];
            }
            $errorMessage = $responseData['message'] ?? 'Unknown error';
            $errorData = $responseData['data'] ?? [];
            throw new DocsignException("Something went wrong: $errorMessage", 0, null, $errorData);
        }

        throw new DocsignException("Unexpected API response.");
    }

    protected function handleDeleteResponse($response, $relatedDocument)
    {
        if ($response->status() == 404) {
            throw new DocsignException("Document ID is invalid.");
        }

        $responseData = $response->json();

        if (isset($responseData['success'])) {
            if ($responseData['success']) {
                return [
                    'apiresponse' => $responseData,
                    'documentBeforeDeletion' => $relatedDocument,
                ];
            }
            $errorMessage = $responseData['message'] ?? 'Unknown error';
            $errorData = $responseData['data'] ?? [];
            throw new DocsignException("Something went wrong: $errorMessage", 0, null, $errorData);
        }

        throw new DocsignException("Unexpected API response.");
    }


    public function createDocument(array $data)
    {
        $response = Http::post('https://docsign.se/api/documents', array_merge($data, ['api_key' => $this->apiKey]));
        $responseData = $response->json();

        if (config('docsign.callbacks.enabled')) {
            $data['callback_url'] = config('docsign.callbacks.document_complete_callback_url');
            $data['callback_sign_url'] = config('docsign.callbacks.party_sign_callback_url');
        }

        if (isset($responseData['success']) && $responseData['success']) {
            $documentId = $responseData['document_id'] ?? null;
            if ($documentId) {
                return $this->getDocument($documentId);
            }
            throw new DocsignException("Document ID missing in response.");
        }

        return $this->handleResponse($response);
    }

    public function getDocuments()
    {
        $response = Http::get('https://docsign.se/api/documents', ['api_key' => $this->apiKey]);
        return array_map(fn($doc) => new Document(array_merge($doc, [
            'parties' => array_map(fn($party) => new SignedParty($party), $doc['parties'] ?? [])
        ])), $response->json());
    }

    public function getDocument($id)
    {
        $response = Http::get("https://docsign.se/api/documents", ['api_key' => $this->apiKey, 'id' => $id]);
        $documents = $response->json();

        if (count($documents) == 0) {
            throw new DocsignException("The document could not be loaded (is the ID correct?).");
        }

        return new Document($documents[0]);
    }

    public function createParty(array $data)
    {
        $response = Http::post('https://docsign.se/api/parties', array_merge($data, ['api_key' => $this->apiKey]));
        $responseData = $response->json();

        if (isset($responseData['success']) && $responseData['success']) {
            $partyId = $responseData['party_id'] ?? null;
            if ($partyId) {
                return new Party(array_merge($data, ['id' => $partyId]));
            }
            throw new DocsignException("Party ID missing in response.");
        }

        return $this->handleResponse($response);
    }

    public function getParties()
    {
        $response = Http::get('https://docsign.se/api/parties', ['api_key' => $this->apiKey]);
        return array_map(fn($party) => new Party($party), $response->json());
    }

    protected function editDocument($documentId, $action)
    {
        $response = Http::post('https://docsign.se/api/document/edit', [
            'api_key' => $this->apiKey,
            'document_id' => $documentId,
            'action' => $action,
        ]);

        return $this->handleResponse($response, $documentId);
    }

    public function pauseDocument($documentId)
    {
        return $this->editDocument($documentId, 'pause');
    }

    public function resumeDocument($documentId)
    {
        return $this->editDocument($documentId, 'resume');
    }

    public function archiveDocument($documentId)
    {
        return $this->editDocument($documentId, 'archive');
    }

    public function unarchiveDocument($documentId)
    {
        return $this->editDocument($documentId, 'unarchive');
    }

    public function deleteDocument($documentId, $pauseIfPending = false)
    {
        $relatedDocument = $this->getDocument($documentId);

        if ($pauseIfPending && $relatedDocument->state === 'pending') {
            $this->pauseDocument($documentId);
        }

        $response = Http::post('https://docsign.se/api/document/edit', [
            'api_key' => $this->apiKey,
            'document_id' => $documentId,
            'action' => 'delete',
        ]);

        return $this->handleDeleteResponse($response, $relatedDocument);
    }

    public function getAccount()
    {
        $response = Http::get('https://docsign.se/api/account', ['api_key' => $this->apiKey]);
        return new Account($response->json());
    }

    public function getParty($id)
    // Temporary implementation until the API supports fetching a single party by ID
    {
        $parties = $this->getParties();

        foreach ($parties as $party) {
            if ($party->id == $id) {
                return $party;
            }
        }

        throw new DocsignException("Party with ID {$id} not found.");
    }
}
