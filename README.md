# Laravel Github to Notion Webhooks

Welcome to the documentation for the Laravel Github to Notion Webhooks package! This package enables you to seamlessly sync events from GitHub, such as pull requests and issues, to Notion. It takes care of the configuration and syncing process, allowing you to focus on your work. Let's dive in and get started!

The package uses another package called [Laravel-Notion-Integration](https://github.com/Eyadhamza/Laravel-Notion-Integration) that allows you to interact with notion api.

## Installation

You can install the package via composer:

```bash
composer require pi-space/laravel-github-to-notion-webhooks
```

After installation, you'll need to publish the config file to customize the package settings:

```bash
php artisan vendor:publish --tag="laravel-github-to-notion-webhooks-config"
```
This is the published config file:

```php
return [
    'github' => [
        'secret' => env('GITHUB_WEBHOOK_SECRET'),
        'events' => [
            'issue' => true,
            'pull_request' => true,
        ],
    ],
    'notion' => [
        'databases' => [
            'issues' => 'cc66f47f03cb4b62a774f6d5f34463ce',
            'pull-requests' => 'b99a0a1287d142bbbd7bec87fe1e6406',
            'users' => '430cedc2495348df926ca520e1255182',
        ],
    ]
];

```

The published config file contains two main sections: `github` and `notion`.

### GitHub Configuration

The `github` section specifies some configurations for the GitHub webhook, including the events you want to listen to and the secret you'll use to verify webhook requests to your Laravel app.
Note that you need to add the secret to your .env file. 
For more information about GitHub webhooks and how to set them up, you can refer to the GitHub documentation [here](https://docs.github.com/en/developers/webhooks-and-events/about-webhooks).

In your GitHub repository settings, you need to do two things:
1. Add the URL of your Laravel app as the webhook URL, which will be the base URL of your app + `/github/webhooks`. For example: `https://example.com/github/webhooks`.
2. Specify the events that you want github to send to your app, after doing so, you will add them to the events key in the config file, and if you want to disable any event, you can set its value to false.

### Notion Configuration

The `notion` section specifies the Notion database IDs where you want to store the synced data. You can either create the Notion databases manually or use an artisan command that will generate the databases for you and return the IDs. If you choose the latter option, follow the steps below:
> note that this will generate a specific database with some specified properties names and types, If you want to customize the properties please refer to the [#customization](#customization) section.

1. Call the artisan command to create the databases in Notion and get the IDs:

```bash
php artisan notion:create-databases {parentPageId}
```

Note that `parentPageId` is the ID of the page where you want to create the databases. You can find this ID by going to the page, clicking on "Share," and copying the link. The ID is the last part of the link after the page name.

The command will return something like this:

```bash
Issues database created successfully with id: cc66f47f03cb4b62a774f6d5f34463ce
Pull Requests database created successfully with id: b99a0a1287d142bbbd7bec87fe1e6406
Users database created successfully with id: 430cedc2495348df926ca520e1255182
```
2. Paste the IDs in the config file under the `notion.databases` section.

## Usage

Then?
No, that's it!, you are good to go, the package will take care of the rest, you can now go to your github repo and create an issue or a pull request and you will see the data in notion, now if you want to either change the properties names or types you may continue with the docs, or if you want to listen to other events that are not supported by the package refer to the contribution section and then send a PR, each event type won't take 15 minutes in writing!


## Customization

If you want to customize the properties names or types for the databases in Notion, this section is for you to make the necessary adjustments.

#### Mapping to Notion Database
The package maps the data from GitHub to Notion based on a set of transformers. for example the issue transformer is responsible for mapping the issue data from GitHub to the issue database in Notion, and the same goes for the pull request transformer, and the user transformer.
You can create your own transformers and map the data as you like, but please note the following:
1. The transformer must implement a certain interface, for example in IssueTransformer you have to implement the IssueTransformerInterface and then bind your own implementation to the interface in your service provider like this:
```php
$this->app->bind(IssueTransformerInterface::class, IssueTransformer::class);
```
2. Also note that the database needs to have a text column called ID, this unique ID is used to identify the record in the database, and it is used to update the record if it already exists, so make sure to add this column to your database.
3. The keys of the transformer needs to be the same as the base Transformer, they are basically the Entity properties names. 

That's it, you are good to go, you can now customize the transformers as you like, you may refer to the [Laravel-Notion-Integration](https://github.com/Eyadhamza/Laravel-Notion-Integration) Documentation to see how to map the properties to your notion database.

## Contributing
The package now only supports issues and pull requests, if you want to add support for other events, you are more than welcome to do send a PR, I will explain the package internals so you can easily understand how to add support for other events.

### Package Internals

#### The Webhook Controller
The webhook controller is responsible for receiving the webhook requests from GitHub, it firsts run two middlewares, the first one is the VerifyWebhookSignature middleware, which is responsible for verifying the request signature - making sure the requests are indeed from github, and the second one is the VerifyWebhookEvent middleware, which is responsible for verifying the event type, and then it merges the event type as Enum "GithubEventTypeEnum" with the payload and passes it to the GithubRequest class.

#### The GithubRequest Class
The GithubRequest class is responsible for parsing the payload and returning the data in a structured way, such as creating the associated entity and its associated user.

#### GitHub Entities
The package has three entities, Issue, PullRequest, and User, each entity has its own transformer, and each transformer is responsible for mapping the data from GitHub to the Notion database.
The entity also sets the notion database id, sets the class attributes based on the request data, and then calls the transformer to map the data to the notion database.

#### The GithubWebhookHandler
The handler accepts the GithubRequest and then calls the appropriate action, either we should create the page in the database, update it or delete it.

#### Implementing a new event
1. Add the event type to the GithubEventTypeEnum.
2. Create a new entity for the event, for example if you want to add support for the "push" event, you will create a new entity called GithubPush, and extend the GithubEntity class and then implement the abstract methods.
3. Create a transformer class and interface for it, and use it in the mapToNotion method, make sure you bind the interface to the implementation in the package service provider.
4. Create a test, you can use the json payload to test with, and make sure it passes.
5. Send the PR!

## Testing

You can run tests for the package using the following command:

```bash
composer test
```

## Changelog

Please see the [CHANGELOG](CHANGELOG.md) for a detailed history of changes to the package.

## Contributing

If you encounter any issues or have ideas for improvements, we welcome your contributions! Please refer to the [CONTRIBUTING](CONTRIBUTING.md) file for details on how to contribute.

## Security Vulnerabilities

If you discover any security vulnerabilities, please follow our [security policy](../../security/policy) on how to report them.

## Credits

- [Eyad Hamza](https://github.com/Eyadhamza)
- [All Contributors](../../contributors)

## License

The Laravel Github to Notion Webhooks package is open-source software licensed under the [MIT License](LICENSE.md).
