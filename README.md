# mStudio extension webhook definitions

This library contains PHP classes for working with mittwald mStudio extension webhooks.[^1]

> [!TIP]
> This library *only* contains the classes modelling the webhook messages and a
> few services for authenticating these messages. If you are using [Symfony](http://symfony.com),
> we recommend using the [mstudio-ext-bundle Symfony Bundle](https://github.com/mittwald/mstudio-ext-bundle),
> instead.

[^1]: https://developer.mittwald.de/docs/v2/contribution/reference/webhooks/

## Installation

Install using Composer:

```
$ composer require mittwald/mstudio-ext-webhooks
```