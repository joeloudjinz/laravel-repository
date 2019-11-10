# Repository pattern generator

This package helps you get started quickly to use **repository pattern** in your next, or current, laravel project because after watching those laracon videos (which by the way are A LOT :p) that talks about design patterns and SOLID principals you became all hipped to try and adapt them, at least i'm :D.

---

## Content

- [introductory](#introductory)
- [installation](#installation)
- [configuration](#configuration)
- [usage](#usage)
  - [generating contract & implementation](#generating-1)
- [Tests](#tests)

---

## <a id="introductory">Introductory</a>

Using this package will help you spend more time focusing on your application's logic by taking care of generating and implementing a layer that sits between the logic and the database.
It generates:

- **Repository Contract**:
  which is an empty interface that can be used to add custom methods to the repository implementation class so you can extend the functionalities, it's also used during binding process.

- **Implementation Class**:
  a class in which the repository logic reside, it extends `Inz\Abstractions\AbstractRepository` and implements the generated contract.

## <a id="installation">Installation</a>

```shell
composer require inz/repository --dev
```

then, publish the configuration file so the package can do it's work properly:

```shell
php artisan vendor:publish tag=inz-repository
```

this will copy the package's configuration file into config folder of the application, more about it in [configuration](#configuration) section

## <a id="configuration">Configuration</a>

The configuration file for the package contains a group of settings which can be modified to satisfy your needs, they have a hopefully clear comments that describes their purposes.

```php
    /*
    |--------------------------------------------------------------------------
    | Base Values
    |--------------------------------------------------------------------------
    | this array give you the ability to define base path & namespace to be used by the package,
    | path is used to create the base directory in which all generated files are stored, on
    | the other hand, namespace is used to define the base namespace for those files.
    |
    | Note that (in the comments) the slash & anti-slash, for path & namespace
    | respectively, are required when defining these values.
    |
     */
    'base' => [
        'path' => app()->basePath(), // 'app/' which is the best choice
        'namespace' => app()->getNamespace(), // 'App\' which is the best choice
    ],
```

- **path** is used to create root directory for the files.
- **namespace** is used to set a namespace for the file.

```php
    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    | this array define the namespaces related to each category of the generated classes
    | by the package, you can re-define these values to control under what namespace
    | the these classes should reside.
    |
    | Note that anti-slash in the end is not set so make sure you don't add it.
     */
    'namespaces' => [
        'contracts' => 'Repositories\Contracts',
        'implementations' => 'Repositories\Implementations',
    ],
```

- **namespaces** array determines the namespaces for different files, note that `App\` is not present because it will be added if specified.

```php
    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    | this array defines the path for each category of the generated classes by the package,
    | you can re-define these values to control where the files should be stored, by
    | default they are stored in the application directory.
    |
    | Note that anti-slash in the end is not set so make sure you don't add it.
     */
    'paths' => [
        'contracts' => 'Repositories/Contracts',
        'implementations' => 'Repositories/Implementations',
    ],
```

- **paths** array determines the paths for different files, `Repositories` represent the root directory for them and what goes after the slash is the directory for those files, note that `app/` is not present because it will be added if specified.

## <a id="usage">Usage</a>

### <a id="generating-1">Generating contract & implementation</a>

To generate a full scaffold, you run

```shell
// the pattern
php artisan make:repository Model

// for a model in app directory
php artisan make:repository Post

// for a model in Models directory
php artisan make:repository Models/Post

// for a model in Models directory in a subdirectory
php artisan make:repository Models/Blog/Post
```

#### Notes

- if the model doesn't exist, you will be asked if you want to create it, if so it will be created.

- If one of the other files exist already you will be asked to override it, if so it will, thus **be careful** about this situation to avoid losing written code.

## <a id="tests">Tests</a>

Hopefully, each functionality or feature is tested in this package.
