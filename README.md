# Kernel

This is a helper package, and it isn't meant to be be used independently. That being said, it contains some nice helper classes to deal with file system, strings, arrays, naming conventions etc. If you need such functionalities, you may find this package helpful.

On the other hand, its real purpose is to collect some classes and methods that are being used by multiple packages that are part of **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**

## Installation
```
sail composer require bakgul/kernel
```
## Commands
This packages ships with 3 console commands.

### Publish Config
Before you start using one of the main packages, you should publish the settings in order to be able to modify them.
```
sail artisan packagify:publish-config
```
#### Arguments
This command has no argument.
#### Options
+ Force: To make it work, append " **-f** " or " **--force** " to the command. When it's passed, the config file will be regenerated, and all the changes you made will be lost.

### Publish Stubs
If any stub doesn't meet your needs, you can edit them as you wish. But first, you have to publish them. It's safe to delete the unedited stubs after publishing. 
```
sail artisan packagify:publish-stub
```
#### Arguments
This command has no argument.
#### Options
+ Force: To make it work, append " **-f** " or " **--force** " to the command. When it's passed, the stubs will be swapped with the default ones.
### Count Code Lines
```
sail artisan count {path?}
```
If you want to know how many lines of code is written on any path, you can use this command. Please note that, this will count everything but the empty lines. 
#### Arguments
+ Path: It should be the relative path to the base path like "app." So the base will be prepended by the code. If it isn't passed, all repository including vendors and node_modules folders will be counted.

## Packagified Laravel

The main package that includes this one can be found here: **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**

## The Packages That Dependent On This One
+ **[Command Evaluator](https://github.com/bulentAkgul/command-evaluator)**
+ **[File Content](https://github.com/bulentAkgul/file-content)**
+ **[File History](https://github.com/bulentAkgul/file-history)**
+ **[Laravel File Creator](https://github.com/bulentAkgul/laravel-file-creator)**
+ **[Laravel Resource Creator](https://github.com/bulentAkgul/laravel-resource-creator)**
+ **[Laravel Package Generator](https://github.com/bulentAkgul/laravel-package-generator)**