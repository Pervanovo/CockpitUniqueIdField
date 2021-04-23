# CockpitUniqueIdField
Addon to agentejo/Cockpit with a field type that is initialized with a generated hexadecimal unique id.

The generated id is unique across the same field name on the same collection.

## Installation
Clone this repo into addon/CockpitUniqueIdField in your cockpit root directory.

## Options
`length` (default: 8) Sets the length of the generated unique id

## Unique id function implementation
The implementation is stolen from https://www.php.net/manual/en/function.uniqid.php#120123
