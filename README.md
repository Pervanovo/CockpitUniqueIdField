# CockpitUniqueIdField
Addon to [agentejo/Cockpit](https://github.com/agentejo/cockpit) with a field type that is initialized with a generated hexadecimal unique id.

The generated id is unique across the same field on the same collection.

## Requirements
Requires either [`random_bytes`](https://www.php.net/manual/en/function.random-bytes.php) or [`openssl_random_pseudo_bytes`](https://www.php.net/manual/en/function.openssl-random-pseudo-bytes.php) functions in PHP.

## Installation
Clone this repo into `addon/CockpitUniqueIdField` in your cockpit root directory.

## Options
`length` (default: 8) Sets the length of the generated unique id.

## Unique id function implementation
The implementation is stolen from https://www.php.net/manual/en/function.uniqid.php#120123
