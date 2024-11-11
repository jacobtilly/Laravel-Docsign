# Laravel-Docsign

A Laravel package to seamlessly integrate with the Inleed DocSign API for digital signatures (using email or Swedish "bankid"), enabling you to manage Docsign documents and parties directly from your Laravel application.


## Buy me a coffee?

If you use this package, please consider [buying me a coffee](https://buymeacoffee.com/jacobtilly) :)


## Installation

Install the project with composer:

```bash
composer require jacobtilly/laravel-docsign
```

### Obtain an API key
Obtain an API key from the official [Docsign website](https://docsign.se).

### Automatic setup

To setup the configuration and to export the config, as well as the jobs (see the section about Callbacks), run the following Artisan command:

```bash
php artisan docsign:install
```
This will publish the config/docsign.php, and create two jobs to your Laravel application: `DocsignDocumentCompleteJob` (dispatched when every party has signed a document created through this package) and `DocsignPartySignJob` (dispatched when someone signs a document created through this package).

### Manual setup
Set your Inleed Docsign API key in your `.env`file:
```
DOCSIGN_API_KEY="your key here"
```

Publish the configuration with 
```
php artisan vendor:publish --provider="JacobTilly\LaravelDocsign\LaravelDocsignServiceProvider"
```
## Configuration
The configuration file grabs your API key from the .env file as well as allows you to setup the callback functionality. Please see the section on callbacks for more information.

## Usage

Please see the official API documentation for more information about how to use the methods.

#### Get all documents

```php
Docsign::getDocuments()
```

#### Get a document

```http
Docsign::getDocument($document_id)
```

#### Get all parties

```php
Docsign::getParties()
```

#### Get a party

```php
Docsign::getParty($party_id)
```

#### Create a document
```php
Docsign::createDocument($data)
```
$data is an array with the items included from the official API documentation. Note, however, that callback URLs should not be included if using the automatic callback handling from the package (they will automatically be included).

#### Create a party
```php
Docsign::createParty($data)
```
$data is an array with the items included from the official API documentation.

#### Pause/unpause a document
```php
Docsign::pauseDocument($document_id)
Docsign::unpauseDocument($document_id)
```
Will pause/unpause a document provided it is pending – a signed document cannot be paused.

#### Archive/unarchive a document
```php
Docsign::archiveDocument($document_id)
Docsign::unarchiveDocument($document_id)
```
Will archive/unarchive a document provided it is signed – only signed documents can be archived.

#### Delete a document
```php
Docsign::deleteDocument($document_id, $force = false)
```
Will delete a document. Only completed documents and paused document can be deleted. If the second force parameter is included and true, it will attempt to pause the document before deleting it (ie force-deleting a pending document).

#### Get Account information
```php
Docsign::getAccount()
```


## Credits
This package is not officially affiliated with Inleed DocSign. DocSign is a service provided by Inleed for electronic signatures. For more information, visit docsign.se.


## License

This package is licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.

> Copyright (c) 2024, Jacob Tilly <dev@jacobtilly.com>
> 
> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
> 
> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.



## Contributing

Contributions are always welcome! Send a pull request or an issue to contribute.
