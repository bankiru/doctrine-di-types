[![Code Coverage](https://scrutinizer-ci.com/g/bankiru/doctrine-di-types/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bankiru/doctrine-di-types/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bankiru/doctrine-di-types/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bankiru/doctrine-di-types/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f92d0cb4-da25-408e-853e-0a0508a77a55/mini.png)](https://insight.sensiolabs.com/projects/f92d0cb4-da25-408e-853e-0a0508a77a55)

[![Latest Stable Version](https://poser.pugx.org/bankiru/doctrine-di-types/v/stable)](https://packagist.org/packages/bankiru/doctrine-di-types)
[![Total Downloads](https://poser.pugx.org/bankiru/doctrine-di-types/downloads)](https://packagist.org/packages/bankiru/doctrine-di-types)
[![Latest Unstable Version](https://poser.pugx.org/bankiru/doctrine-di-types/v/unstable)](https://packagist.org/packages/bankiru/doctrine-di-types)
[![License](https://poser.pugx.org/bankiru/doctrine-di-types/license)](https://packagist.org/packages/bankiru/doctrine-di-types)


# Doctrine DI types

DI-capable doctrine types

## Purpose

Bring DI capabilities to statically initialized doctrine types.

## Configuration reference

```yaml
doctrine_di_types:
    enabled:              false

    # Forces DI type initialization while bundle boots
    init_on_boot:         true

    # Service ID for custom service initialization hook
    init_service:         doctrine
```

## Extending
