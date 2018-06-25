# Resora
## Compatibility

- 1.0.* - Laravel version 5.6.*

## Methodology Flow Chart
[https://github.com/railroadmedia/resora/blob/master/documentation/flow-charts/data-flow-methodology.jpg](https://github.com/railroadmedia/resora/blob/master/documentation/flow-charts/data-flow-methodology.jpg)

## Install Instructions

### For Use In A Package

1. Include in your composer.json

```json
"railroad/resora": "1.0.*"
```

1. Configure decorators in your packages service provider when required.

```php
config()->set(
    'resora.decorators.my-packages-repository',
    [UserFieldDecorator::class, UserEntityDecorator::class]
);
```

### For User In An Application