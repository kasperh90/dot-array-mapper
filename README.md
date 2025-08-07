#  DotArrayMapper

Mapping array data using dot notation.

# Installation

```bash
composer require kasperhansen/dot-array-mapper
```

# Usage

```php
use Kasperhansen\DotArrayMapper;

$mapper = new DotArrayMapper();

// Set the data to be mapped
$mapper->setData([
    'user' => [
        'name' => 'John Doe',
        'email' => 'john@doe.com',
        'address' => [
            'city' => 'New York',
        ],
    ],
]);

// Define the mapping using dot notation
$mapper->map([
    'name' => 'user.name',
    'email' => 'user.email',
    'city' => 'user.address.city',
]);

// Extract the mapped data
$mappedData = $mapper->extract();

// Output the mapped data
echo $mappedData['name']; // John Doe
echo $mappedData['email']; // john@doe.com
echo $mappedData['city']; // New York
```

# License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

# Contributing
Contributions are welcome!