# bestit/commercetools-odm-bundle

Makes the commercetools database accessible with the doctrine commons api. 
It still uses the [commmercetools/php-sdk](https://github.com/commercetools/commercetools-php-sdk) under the hood.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bestit/commercetools-odm-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new BestIt\CommercetoolsODMBundle\BestItCommercetoolsODMBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configure the Bundle

```
# Default configuration for "BestItCommercetoolsODMBundle"
bestit_commercetools_odm:

    # Please provide the service id for your commercetools client.
    client_service_id:    ~ # Required

    # Please provide the service id for your commercetools request async pool.
    pool_service_id:      ~
```

## Usage

### Event Listener with the sercice container

You can add an event listener to the typical live cycle events if you tag your service with the name 
_best_it_commercetools_odm.event_listener_. But don't forget the name of the event with the key _event_ on your tag.

Example:

```yaml
# app/config/services.yml
services:
    app.tokens.action_listener:
        class: AppBundle\EventListener\TokenListener
        arguments: ['%tokens%']
        tags:
            - { name: best_it_commercetools_odm.event_listener, event: postPersist }
```

**the method of the service should match the event name.**

### Services

The service _best_it.commercetools_odm.manager_ provices you with an extended _Doctrine\Common\Persistence\ObjectManager_.

**the method of the service should match the event name.**

### Filters

You can add multiple filters to apply on requests. Just create one filter, implement the _FilterInterface_ and tag the service with _best_it_commercetools_odm.filter_.
The filter get the raw created request and will be applied just before the request will be send.

Example:
```php
// ProductFilter.php
class ProductFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function apply($request)
    {
        $request->setExpands(['masterVariant.attributes[*].value', 'productType', 'categories[*].ancestors[*]']);
        
        $request->channel('xyz');
        $request->currency('EUR');
    }
}
```

```yaml
# app/config/services.yml
services:
    app.filter.product_filter:
        class: AppBundle\Filter\ProductFilter
        tags:
            - { name: best_it_commercetools_odm.filter }
```

Now you can apply the one or more filter whenever you want:

```yml
    app.repository.product_projection:
        class: BestIt\CommercetoolsODM\Model\ProductProjectionRepository
        factory: ["@best_it.commercetools_odm.manager", getRepository]
        arguments:
            - Commercetools\Core\Model\Product\ProductProjection
        calls:
            - [filter, ['projection', 'projection-categories']]
```