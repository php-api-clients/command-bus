# command-bus

[![Linux Build Status](https://travis-ci.org/php-api-clients/command-bus.svg?branch=master)](https://travis-ci.org/php-api-clients/command-bus)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/dvcu9l8rm6shy7t3?svg=true)](https://ci.appveyor.com/project/php-api-clients/command-bus)
[![Latest Stable Version](https://poser.pugx.org/api-clients/command-bus/v/stable.png)](https://packagist.org/packages/api-clients/command-bus)
[![Total Downloads](https://poser.pugx.org/api-clients/command-bus/downloads.png)](https://packagist.org/packages/api-clients/command-bus)
[![Code Coverage](https://scrutinizer-ci.com/g/php-api-clients/command-bus/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-api-clients/command-bus/?branch=master)
[![License](https://poser.pugx.org/api-clients/command-bus/license.png)](https://packagist.org/packages/api-clients/command-bus)
[![PHP 7 ready](http://php7ready.timesplinter.ch/php-api-clients/command-bus/badge.svg)](https://appveyor-ci.org/php-api-clients/command-bus)

Light weight wrapper around league/tactician adding promised based interface(s) and next tick execution.

# Installation

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require api-clients/command-bus 
```

# Usage

Simply pass the command bus a [`ReactPHP` event loop](https://github.com/reactphp/event-loop) and a `CommandHandlerMiddleware` and it will run each handle in a future tick on the passed event loop.

```PHP
$commandHandlerMiddleware = new CommandHandlerMiddleware(/*...*/);
$commandBus = new CommandBus(
    $container->get(LoopInterface::class),
    $commandHandlerMiddleware
);
```

There is one note though, command handlers are encouraged to return promises them self. 

# License

The MIT License (MIT)

Copyright (c) 2016 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

