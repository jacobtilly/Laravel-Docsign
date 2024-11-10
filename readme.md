# Laravel Inleed DocSign Integration

A Laravel package to seamlessly integrate with the Inleed DocSign API for digital signatures (using email or Swedish "bankid"), enabling you to manage Docsign documents and parties directly from your Laravel application.

## Installation

To install the package, use Composer:

```bash
composer require jacobtilly/laravel-docsign
```

Add your Docsign API key to the .env file:

```
DOCSIGN_API_KEY=your-docsign-api-key
```

## Publish Configuration
The configuration file can be published using

```bash
php artisan vendor:publish --provider="JacobTilly\LaravelDocsign\LaravelDocsignServiceProvider"
```

## Obtain a DocSign API Key
To obtain an API key, register and log in at docsign.se. Follow their instructions to generate an API key.

## Documentation
Below are the methods provided by this package and the fields required for each operation.

### Retrieve All Documents

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$documents = Docsign::getDocuments();
```

This method retrieves all documents available in your DocSign account. Each document object includes:
* id: The unique identifier for the document.
* name: The name of the document.
* state: The state of the document (e.g., pending, completed).
* comment: Any comments associated with the document.
* createdAt: The creation date of the document.
* originalPdfUrl: URL to the original PDF.
* signedPdfUrl: URL to the signed PDF, null until signed.
* parties: An array of parties associated with the document.

### Retrieve a Specific Document

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$document = Docsign::getDocument($id);
```

This method retrieves one document with specified ID. Returns follow the document format from the `getDocuments()` method.

### Retrieve All Parties

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$parties = Docsign::getParties();
```

This method retrieves all parties available in your DocSign account. Each party object includes:
* id: The unique identifier for the party.
* name: The name of the party.
* company: The company the party is associated with.
* email: The email address of the party.
* phoneNumber: The phone number of the party.
* signMethod: The method of signing (e.g., bankid, email).
* externalId: An optional external identifier from your own system.

### Retrieve Account Information

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$account = Docsign::getAccount();
```
This method retrieves your Inleed DocSign account information, including:
* email: The email associated with your DocSign account.
* balance: The current balance in your DocSign account, shown in "ören".

### Create a Party

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$createPartyData = [
  "name" => "Jacob Tilly", // Required
  "company" => "Jacobs firma",
  "email" => "example@example.com", // Required if no phone number
  "phone_number" => "+46701234567", // Required if no email
  "sign_method" => "bankid", // Required, bankid or email
  "external_id" => "1338" // Optional, ID from your own system
];

try {
    $party = Docsign::createParty($createPartyData);
    echo "Party created with ID: " . $party->id;
} catch (DocsignException $e) {
    echo "Error: " . $e->getMessage();
}
```

This method creates a party ("undertecknare") with the specified information. On success, it will return the created party. Please note that the Docsign API only returns the created party ID on creation, and does not have an endpoint for retrieving a single party. For convenience, this method returns a Party object, but please note that the fields (other than ID) are just the request data repeated back, without any validation against the API. 

> **Note**: If the createParty method is called with data including an external_id that equals the external_id of an existing party, a new party will not be created (and the existing one will not be updated). In this case, as the method returns a Party object where all fields except ID are just the request data repeated back, the Party object returned might not correspond to the actual party that exists in the Docsign platform.

### Create a Document

```php
use JacobTilly\LaravelDocsign\Facades\Docsign;

$createDocumentData = [
  "name" => "Agreement", // Required
  "parties" => [12345, 23456], // Required, at least one valid party ID
  "attachments" => [
    [
      "name" => "Sample PDF", // Required
      "url" => "https://pdf.com/samplepdf.pdf" // Required if no base64_content
    ]
  ],
  "send_reminders" => true,
  "comment" => "Please sign!",
  "send_receipt" => true,
  "send_notifications" => false,
  "callback_url" => "https://example.dev/callback",
  "callback_sign_url" => "https://example.dev/callback/party-sign"
];

try {
    $document = Docsign::createDocument($createDocumentData);
    echo "Document created: " . $document->name;
} catch (DocsignException $e) {
    echo "Error: " . $e->getMessage();
}
```

Creates a party. For possible parameters, please refer to the Docsign API reference.

## Further Documentation
For more detailed documentation on the Inleed DocSign API, visit DocSign's documentation page. Note that you will need an account to access it.

## Credits
This package is not officially affiliated with Inleed DocSign. DocSign is a service provided by Inleed for electronic signatures. For more information, visit docsign.se.

## License
This package is licensed under the MIT License. See the LICENSE file for more information.

## Contributing
Contributions are welcome! Please open an issue or submit a pull request.

## Buy me a coffee?
If you use this package, please consider [https://buymeacoffee.com/jacobtilly](buying me a coffee) :)
