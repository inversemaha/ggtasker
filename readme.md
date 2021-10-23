## Installation & Usage

### Installation
To install this package from the Github server into a new Laravel project using Composer.
Create another Laravel project on your local machine.

### Require our package in composer.json
Edit the new project’s composer.json file to require our package and also indicate our
repository in Github for Composer to know where to fetch packages aside from the default packagist repositories.

```json
...
"require": {
    "php": "^7.3|^8.0",
    "fruitcake/laravel-cors": "^2.0",
    "guzzlehttp/guzzle": "^7.0.1",
    "laravel/framework": "^8.65",
    "laravel/sanctum": "^2.11",
    "laravel/tinker": "^2.5"
    "Gglink/Ggtasker": "^0.1.0"
    },
....
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/inversemaha/ggtasker"
    }
]
....
```
The repository’s property lists all non-packagist repositories. If you are going to install
several packages from the same Github domain, instead of specifying each package’s repository
you may use the type “composer” and only indicate the domain in the URL.
However, there’s an additional setup that your Github admin should do in this case.
For now, let’s stick to a single repository with type “vcs”.

Then run `composer update` in your terminal to pull it in.

```bash
$ composer update
```
Now this package should find the vendor directory of the project and should be able to use
package require in project. 

# example usage
```php
$apiBaseURL = env("GGTASKER_API_BASE_URL");
$apiKey = env("GGTASKER_API_KEY"");

$ggtakerApi = new Ggtasker($apiBaseURL, $apiKey);

# login and get token
$result = $ggtakerApi->login("test", "password123");
$token = $result["Token"];

# add new user
$newUser = [
    "Username" => "t999",
    "Email" => "t999@example.com",
    "Password" => "test999",
    "Group" => ["UserAdmin", "Accountant"]
];
$result = $ggtakerApi->addUser($newUser, $token);
dd($result);
```
