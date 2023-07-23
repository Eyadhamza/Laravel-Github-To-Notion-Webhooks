# Laravel Github to Notion Webhooks

Welcome to the documentation for the Laravel Github to Notion Webhooks package! This simple package enables you to effortlessly sync events from GitHub, such as pull requests and issues, directly to Notion. By taking care of the configuration and syncing process, it allows you to focus on your work. Let's dive in and get started!

The package leverages another package called [Laravel-Notion-Integration](https://github.com/Eyadhamza/Laravel-Notion-Integration), which allows you to interact seamlessly with the Notion API.

## Installation

To begin using the package, install it via composer:

```bash
composer require pi-space/laravel-github-to-notion-webhooks
```

After installation, you'll need to publish the configuration file to customize the package settings:

```bash
php artisan vendor:publish --tag="laravel-github-to-notion-webhooks-config"
```

The published configuration file looks like this:

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

The `github` section specifies configurations for the GitHub webhook, including the events you want to listen to and the secret you'll use to verify webhook requests to your Laravel app.
To use the package, you need to add the secret to your .env file.
For more information about GitHub webhooks and how to set them up, refer to the GitHub documentation [here](https://docs.github.com/en/developers/webhooks-and-events/about-webhooks).

In your GitHub repository settings, you need to do two things:
1. Add the URL of your Laravel app as the webhook URL, which will be the base URL of your app + `/github/webhooks`. For example: `https://example.com/github/webhooks`.
2. Specify the events that you want GitHub to send to your app. After doing so, add them to the events key in the config file, and if you want to disable any event, you can set its value to false.

### Notion Configuration

The `notion` section specifies the Notion database IDs where you want to store the synced data. You can either create the Notion databases manually or use an artisan command that will generate the databases for you and return the IDs. If you choose the latter option, follow these steps:

> Note that this will generate a specific database with predefined property names and types. If you want to customize the properties, please refer to the [#customization](#customization) section.
> Note that the Users Database in Notion is mandatory, as it will be used to identify the GitHub username and map it to the Notion user. Make sure to populate the table manually, writing each GitHub username, and then mentioning the corresponding Notion user in the Person property.

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

That's it! You're all set! The package will take care of the rest. Now you can head over to your GitHub repository, create an issue or a pull request, and you'll see the data automatically synced to Notion. If you wish to change the property names or types, you may continue with the documentation. Additionally, if you want to listen to other events not currently supported by the package, refer to the contribution section and send a pull request.

## Customization

If you want to customize the property names or types for the databases in Notion, this section is for you. Here's how you can make the necessary adjustments:

### Mapping to Notion Database

The package maps the data from GitHub to Notion based on a set of transformers. For example, the issue transformer is responsible for mapping the issue data from GitHub to the issue database in Notion, and the same goes for the pull request transformer and the user transformer. You can create your own transformers and map the data as you see fit. However, please note the following:

1. The transformer must implement a specific interface. For instance, for the IssueTransformer, you have to implement the IssueTransformerInterface and then bind your own implementation to the interface in your service provider like this:

```php
$this->app->bind(IssueTransformerInterface::class, IssueTransformer::class);
```

2. Also, note that the database needs to have a text column called ID. This unique ID is used to identify the record in the database, and it is used to update the record if it already exists. So make sure to add this column to your database.

3. The keys of the transformer need to be the same as the base Transformer; they are essentially the property names for the entity.

With these changes, you're all set to customize the transformers to your specific needs. For more details on how to map properties to your Notion database, you may refer to the [Laravel-Notion-Integration](https://github.com/Eyadhamza/Laravel-Notion-Integration) Documentation.

## Contributing

The package currently supports only issues and pull requests. If you wish to add support for other events, you are more than welcome to do so by sending a pull request. To help you understand how to add support for other events, let's go over the package internals:

### Package Internals

#### The Webhook Controller

The webhook controller is responsible for receiving webhook requests from GitHub. It first runs two middlewares: the VerifyWebhookSignature middleware, which verifies the request signature to ensure the requests are genuinely from GitHub, and the VerifyWebhookEvent middleware, which verifies the event type. The event type is then merged as an Enum "GithubEventTypeEnum" with the payload and passed to the GithubRequest class.

#### The GithubRequest Class

The GithubRequest class is responsible for parsing the payload and returning the data in a structured way, such as creating the associated entity and its associated user.

#### GitHub Entities



The package has three entities: Issue, PullRequest, and User. Each entity has its own transformer, responsible for mapping the data from GitHub to the Notion database. The entity also sets the Notion database ID, sets the class attributes based on the request data, and then calls the transformer to map the data to the Notion database.

#### The GithubWebhookHandler

The handler accepts the GithubRequest and then calls the appropriate action, either creating the page in the database, updating it, or deleting it.

### Implementing a New Event

If you want to add support for a new event:

1. Add the event type to the GithubEventTypeEnum.
2. Create a new entity for the event. For example, if you want to add support for the "push" event, you will create a new entity called GithubPush, extending the GithubEntity class, and then implementing the abstract methods.
3. Create a transformer class and interface for the new entity. Use it in the mapToNotion method and remember to bind the interface to the implementation in the package service provider.
4. Create a test, using the JSON payload to test with, and ensure it passes.
5. Send the PR!

## Testing

You can run tests for the package using the following command:

```bash
composer test
```

## Changelog

For a detailed history of changes to the package, please see the [CHANGELOG](CHANGELOG.md).

## Security Vulnerabilities

If you discover any security vulnerabilities, please follow our [security policy](../../security/policy) on how to report them.

## Credits

- [Eyad Hamza](https://github.com/Eyadhamza)
- [All Contributors](../../contributors)

## License

The Laravel Github to Notion Webhooks package is open-source software licensed under the [MIT License](LICENSE.md).

---
