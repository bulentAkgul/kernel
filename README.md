# Kernel

This is a helper package, and it isn't meant to be used independently. That being said, it contains some nice helper classes to deal with the file system, strings, arrays, naming conventions, etc. If you need such functionalities, you may find this package helpful.

On the other hand, its real purpose is to collect some classes and methods that are being used by multiple packages that are part of [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel)

#### DISCLAIMER

It should be production-ready but hasn't been tested enough. You should use it carefully since this package will manipulate your files and folders. Always use a version-control, and make sure you have [**File History**](https://github.com/bulentAkgul/file-history) to be able to roll back the changes.

## Installation

```
sail composer require bakgul/kernel
```

## Commands

This package ships with 4 console commands.

### Publish Config

Before you start using one of the main packages, you should publish the settings to be able to modify them.
```
sail artisan packagify:publish-config
```

#### Arguments

This command has no argument.

#### Options

-   **force**: To make it work, append " **-f** " or " **--force** " to the command. The config file will be regenerated when it's passed, and all the changes you made will be lost.

### Publish Stubs

If any stub doesn't meet your needs, you can edit them as you wish. But first, you have to publish them. It's safe to delete the unedited stubs after publishing.
```
sail artisan packagify:publish-stub
```

#### Arguments

This command has no argument.

#### Options
-   **force**: To make it work, append " **-f** " or " **--force** " to the command. The stubs will be swapped with the default ones when it's passed.

### Display Helps

To display the help content in the terminal, you can use this command.
```
sail artisan get-help {from}
```

#### Arguments

-   **from**: This is the identifier of the other commands that have help content. To see the list of the identifiers, run the command without this argument.

#### Options

This command has no options.

### Count Code Lines

If you want to know how many lines of code are written on any path, you can use this command. Please note that this will count everything but the empty lines.
```
sail artisan count {path?}
```

#### Arguments

-   **path**: It should be the relative path to the base path like "app/Models". All repositories, including vendors and node_modules, will be counted if it isn't passed.

## Packagified Laravel

The main package that includes this one can be found here: [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel)

## The Packages That Dependent On This One

-   [**Command Evaluator**](https://github.com/bulentAkgul/command-evaluator)
-   [**File Content**](https://github.com/bulentAkgul/file-content)
-   [**File History**](https://github.com/bulentAkgul/file-history)
-   [**Laravel File Creator**](https://github.com/bulentAkgul/laravel-file-creator)
-   [**Laravel Resource Creator**](https://github.com/bulentAkgul/laravel-resource-creator)
-   [**Laravel Package Generator**](https://github.com/bulentAkgul/laravel-package-generator)