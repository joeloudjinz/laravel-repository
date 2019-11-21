[![Build Status](https://semaphoreci.com/api/v1/projects/b74ac48e-13ce-4e03-ab12-236f989c9c37/3009082/badge.svg)](https://semaphoreci.com/joe-inz-94/laravel-repository)
![Packagist Version](https://img.shields.io/packagist/v/inz/repository)
![Packagist](https://img.shields.io/packagist/dm/inz/repository)
![PHP from Packagist](https://img.shields.io/packagist/php-v/inz/repository)
![GitHub](https://img.shields.io/github/license/joeloudjinz/laravel-repository)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/joeloudjinz/laravel-repository)
![GitHub repo size](https://img.shields.io/github/repo-size/joeloudjinz/laravel-repository)

# Repository pattern generator

This package helps you get started quickly to use **repository pattern** in your next, or current, laravel project because after watching those laracon videos (which by the way are A LOT :p) that talks about design patterns and SOLID principals you became all hipped to try and adapt them, at least i'm :D.

---

## Content

- [Introductory](#introductory)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Generating contract & implementation](#generating-1)
  - [Binding contract to implementation](#generating-2)
- [Functionalities](#functionalities)
  - [Repository abstract class](#func-1)
  - [Methods](#func-2)
  - [Soft-deleted records](#func-3)
- [Contribution](#contribution)

---

## <a id="introductory">Introductory</a>

Using this package will help you spend more time focusing on your application's logic by taking care of generating and implementing a layer that sits between the logic and the database.
It generates:

- **Repository Contract**:
  which is an empty interface that can be used to add custom methods to the repository implementation class so you can extend the functionalities, it's also used during binding process.

- **Implementation Class**:
  a class in which the repository logic reside, it implements the generated contract.

> **Note**: for now, the minimum laravel version this package is tested on is **5.5**

## <a id="installation">Installation</a>

```shell
composer require inz/repository
```

if you using **laravel 5.4 and below**, you need to add the package's service provider in `config/app.php`

```php
'providers' => [
    ...
    Inz\RepositoryServiceProvider::class,
    ...
],
```

then, you need to publish the configuration file so the package can do it's work properly:

```shell
php artisan vendor:publish --tag=inz-repository
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
    | Note that (in the comments) the anti-slash for namespace required when defining it's value.
    |
     */
    'base' => [
        'path' => app_path(), // 'app folder' which is the best choice
        'namespace' => app()->getNamespace(), // 'App\' which is the best choice
        'providers' => [
            'path' => app_path(),
            'namespace' => app()->getNamespace(),
        ],
    ],
```

- **path** is used to create root directory for the files.
- **namespace** is used to set a namespace for the file.
- **providers** array where you set the base path and namespace of the providers in your application so the
  package can determine where and under what it should define the repository service provider.

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

// for a model in Models folder
php artisan make:repository Models/Post

// for a model in Models folder in a subdirectory
php artisan make:repository Models/Blog/Post
```

#### Notes

- if the model doesn't exist, you will be asked if you want to create it, if so it will be created.

- If one of the other files exist already you will be asked to override it, if so it will, thus **be careful** about this situation to avoid losing written code.

- This command will bind the classes also.
- Don't forget to register the `RepositoryServiceProvider` in `app.php` after it is generated.

### <a id="generating-2">Binding contract to implementation</a>

to bind a contract class to an implementation of a certain model, use the command:

```shell
php artisan bind:repository Model
```

- using the model name, the package can determine the corresponding contract and implementation classes
  > Currently, the package will not check if the classes (model, contract or implementation) exist, this will
  > be fixed in the upcoming version.
- if the service provider doesn't exist it will create one
- if the repository is already bound it won't complete the process.

## <a id="functionalities">Functionalities</a>

### <a id="func-1">Repository abstract class</a>

In `Inz\Base\Abstractions\Repository` you can find the implementation of `Inz\Base\Interfaces\RepositoryInterface` that describes the methods used to access the database.

| Properties                    | Why                                                                                                                                                                                        |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `protected $model;`           | model instance used to query data                                                                                                                                                          |
| `protected $attributes;`      | attributes list of the model, also the list of columns of the table, excluding the ones in `$excludedColumns`.                                                                             |
| `protected $excludedColumns;` | to define the columns that will be excluded when the repository object operates on the table, to add other columns to this array just override it in your repository implementation class. |

### <a id="func-2">Methods</a>

Here is the list of available methods of the repository class:

> **Note**: Repository class should be implemented the way you desire, so use the interface to define the blueprint for it and implement additional methods or override existing ones in the concrete class.

| Method            | Parameters                                         | Return                        | Description                                                                                                          |
| ----------------- | -------------------------------------------------- | ----------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| all();            | array `cols` default `['*']`                       | Collection                    | similar to `all()` of eloquent model                                                                                 |
| first();          |                                                    | Model instance or null        | similar to `first()` of eloquent model                                                                               |
| find();           | mixed `id`                                         | Model instance or null        | similar to `find()` of eloquent model                                                                                |
| findWhere();      | String `column`, mixed `value` & String `operator` | Collection                    | finds all records that match the condition of where clause                                                           |
| findFirstWhere(); | String `column`, mixed `value` & String `operator` | Model instance or null        | finds the first record that matches the condition of where clause                                                    |
| paginate();       | int `count` default `10`                           | LengthAwarePaginator instance | similar to `paginate()` of eloquent model                                                                            |
| save();           | array `data = [column => value]`                   | boolean                       | creates a new instance based on the passed data and persist it to storage                                            |
| update();         | int `id` & array `data = [column => value]`        | boolean                       | updates a record based on the passed data and persist it to storage, if the record doesn't exist `false` is returned |
| delete();         | int `id`                                           | boolean                       | similar to `delete()` of eloquent model                                                                              |
| count();          |                                                    | int                           | similar to `count()` of eloquent model                                                                               |

### <a id="func-3">Soft deleted records</a>

To handle trashed records of the table (if using soft delete trait in the model), use trait `Inz\Base\Traits\TrashedOperations` in repository implementation class, it contains this set of methods:

| Method             | Parameters                   | Return                 | Description                                              |
| ------------------ | ---------------------------- | ---------------------- | -------------------------------------------------------- |
| allTrashed();      | array `cols` default `['*']` | Collection             | returns soft-deleted records only                        |
| allWithTrashed();  | array `cols` default `['*']` | Collection             | returns the whole records of the table                   |
| firstTrashed();    |                              | Model instance or null | fetches the first record from trashed ones only          |
| findTrashed();     | mixed `id`                   | Model instance or null | fetches a record of the passed id from trashed ones only |
| findWithTrashed(); | mixed `id`                   | Model instance or null | fetches a record of the passed id from the whole table   |
| countTrashed();    |                              | int                    | returns the count of trashed records in the table        |
| countWithTrashed() |                              | int                    | returns the count of all records in the table            |

## <a id="contribution">Contribution</a>

Feel free to make any pull request, any contribution is welcomed. If you encountered a problem, use the issues section.
