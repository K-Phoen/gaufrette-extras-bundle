---
layout: layout
theme_dir: jekyll-theme

author: Kévin Gomez
title: gaufrette-extras-bundle
description: Symfony2 bundle integrating the Gaufrette Extras library
project_url: https://github.com/K-Phoen/gaufrette-extras-bundle
---

GaufretteExtrasBundle
=====================

**GaufretteExrasBundle** is a Symfony2 bundle integrating the [Gaufrette Extras](https://github.com/K-Phoen/gaufrette-extras) library.


Installation
============

The recommended way to install this library is through composer.

Just create a `composer.json` file for your project:

```json
{
    "require": {
        "kphoen/gaufrette-extras-bundle": "dev-master"
    }
}
```

And run these two commands to install it:

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

Register the `KPhoenGaufretteExtrasBundle`:

```php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new KPhoen\GaufretteExtrasBundle\KPhoenGaufretteExtrasBundle(),
    );
}
```


Features
========

URL resolvers
-------------

Resolvers provide a quick and easy way to resolve filesystem entries to a URL.
See [GaufretteExtras](https://github.com/K-Phoen/gaufrette-extras).

A Twig extension is also provided, allowing the following things in templates:

```jinja
    <img src="{{ article.thumb|resolve("thumbs") }}" />
```

Here is the associated configuration:

```yaml
# gaufrette bundle
knp_gaufrette:
    adapters:
        thumbs_adapter:
            local:
                directory:  %kernel.root_dir%/../web/thumbs
                create:     true

    filesystems:
        thumbs:
            adapter:        thumbs_adapter

# gaufrette extras
k_phoen_gaufrette_extras:
    resolvers:
        thumbs:                 # the filesystem name
            prefix:             # the resolver to use
                path: /thumbs   # and its configuration
```


Image form type
---------------

ImageType to show the previously uploaded image.

Utilisation sample:

```php
<?php

class MyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('avatar', 'image', array(
            'gaufrette'             => 'avatars',
            'image_path'            => 'avatar', // because there is a getAvatar() method in the data class

            'image_alt'             => 'Avatar',
            'image_width'           => '100px',
            'image_height'          => '100px',
            'no_image_placeholder'  => 'noImage.jpg',
        ));
    }
}
```


License
=======

This bundle is released under the MIT License. See the bundled LICENSE file
for details.
