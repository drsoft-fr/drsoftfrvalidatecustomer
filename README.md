![drSoft.fr](logo.png)

# drSoft.fr Validate Customer

## Table of contents

- [Presentation](#Presentation)
- [Requirements](#Requirements)
- [Install](#Install)
- [Links](#Links)
- [Authors](#Authors)
- [Licenses](#Licenses)

## Presentation

The drSoft.fr Validate Customer module allows administrators to approve new user registrations, while also providing email notifications and automatic user group assignment.

## Requirements

This module requires PrestaShop 8.1 to work correctly.

This library also requires :

for production :

- [composer](https://getcomposer.org/)

for development :

- [composer](https://getcomposer.org/)

## Install

```bash
$ cd {PRESTASHOP_FOLDER}/modules
$ git clone git@github.com:drsoft-fr/drsoftfrvalidatecustomer.git
$ cd drsoftfrvalidatecustomer
$ composer install -o --no-dev
$ cd {PRESTASHOP_FOLDER}
$ php ./bin/console prestashop:module install drsoftfrvalidatecustomer
$ php ./bin/console cache:clear --env=prod --no-debug
```

## Links

- [drSoft.fr on GitHub](https://github.com/drsoft-fr)
- [GitHub](https://github.com/drsoft-fr/drsoftfrvalidatecustomer)
- [Issues](https://github.com/drsoft-fr/drsoftfrvalidatecustomer/issues)

## Authors

**Dylan Ramos** - [on GitHub](https://github.com/dylan-ramos)

## Licenses

see LICENSE file
