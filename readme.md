# Swift Cart

[![Build Status](https://github.com/vaened/swift-cart/actions/workflows/tests.yml/badge.svg)](https://github.com/vaened/swift-cart/actions?query=workflow:Tests)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](license)

This library is a powerful tool that allows you to efficiently manage purchases, quotes, accounts, and more. With this library, you can
effortlessly handle various aspects of a shopping cart, including adding products, taxes, fees, and discounts, and obtaining a detailed
summary of the total amount to pay.

> This library is based on [vaened/php-price-engine](https://github.com/vaened/php-price-engine) for price management

```php
// initialize cart
$taxes = Taxes::from([
    Inclusive::proportional(18, 'IGV')
]);

$cart = new ShoppingCart($taxes);

// add products
$mouse = $cart->push(Product::findOrFail(1), quantity: 2);

// assign individual charges and discounts
$mouse->add(
    Charge::proportional(percentage: 5)->named('Delivery'),
    Charge::fixed(amount: 2)->named('Random'),
);
$mouse->apply(
    Discount::proportional(percentage: 10)->named('NewUser')
);

// update quantity
$mouse->update(quantity: 3);

// assign global charges and discounts
$cart->addAsGlobal(
    Charge::fixed(amount: 2)->named('Express')
);
$cart->applyAsGlobal(
    Discount::proportional(percentage: 1)
);

// get summary
$cart->summary();
```

## Installation

You can install the library using `composer`.

```shell
composer require vaened/swift-cart
```