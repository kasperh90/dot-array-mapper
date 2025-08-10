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
$mapper
    ->setData([
        'user' => [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'address' => [
                'city' => 'New York',
            ],
        ],
    ])
    ->map([
        'name' => 'user.name',
        'email' => 'user.email',
        'city' => 'user.address.city',
    ])
    ->addFilter('name', fn($value) => strtoupper($value))
    ->addFilter('email', fn($value) => strtolower($value));

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