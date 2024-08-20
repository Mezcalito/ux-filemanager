# Mezcalito UX FileManager

## Overview

The Mezcalito File Manager Bundle provides an easy-to-use file management system in your Symfony application using Twig Components and Live Components. It allows you to create and manage multiple storages, each with its unique configuration. Currently, the bundle supports only a local filesystem provider.

![Effortless file management with Symfony UX and Mezcalito UX FileManager](/doc/images/ux-filemanager-promo.png)

## Installation

Add [mezcalito/ux-filemanager](https://packagist.org/packages/mezcalito/ux-filemanager) to your composer.json file:

```bash
composer require mezcalito/ux-filemanager
```

## Register and configure the bundle

If you are using Symfony Flex, the following steps should be done automatically. Otherwise, follow the instructions.

### Register the bundle

Inside `config/bundles.php`, add the following line:

```php
// config/bundles.php

return [
    // ...
    Mezcalito\FileManagerBundle\MezcalitoFileManagerBundle::class => ['all' => true],
];
```

## Configuration

To configure the bundle, add the following configuration to your `config/packages/mezcalito_file_manager.yaml` file. 
This example demonstrates how to set up a local storage: 

```yaml
mezcalito_file_manager:
    storages:
        local:
            uri_prefix: /media
            provider: local
            options:
                path: '%kernel.project_dir%/public/uploads/storages/local'
                media_url: 'https://media.yourdomain.com/'
                ignore_dot_files: true
```

Storage Configuration Options

| Name             | Type   | Required | Default value               | Description                                                                                   |
|------------------|--------|----------|-----------------------------|-----------------------------------------------------------------------------------------------|
| path             | string | Yes      |                             | The path to the directory where files will be stored                                          |
| media_url        | string | No       | domain from current request | The URL used to generate media links. If not set, the current domain will be used             |
| ignore_dot_files | bool   | No       | true                        | If true, hidden files and directories (starting with a dot) will be ignored. Defaults to true |

## Usage

Once the bundle is installed and configured, you can use the file manager in your Twig templates. Here's how to include it in your template:

### Using Twig Syntax
```twig
{{ component('Mezcalito:FileManager:FileSystem', { storage: 'local' }) }}
```

### Using HTML-like Syntax
```twig
<twig:Mezcalito:FileManager:FileSystem storage="local"/>
```

In both cases, replace `local` with the name of the storage you want to use.

## Issues and feature requests

Please report issues and request features at https://github.com/mezcalito/ux-filemanager/issues.

## License

This bundle is under the MIT license. For the whole copyright, see the LICENSE file distributed with this source code.