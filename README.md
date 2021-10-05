[![Build Status](https://travis-ci.com/koschos/php-retry.svg?branch=master)](https://travis-ci.com/koschos/php-retry)

# Overview

PHP library for doing retries.
This is port of spring retry https://github.com/spring-projects/spring-retry

# Not ported

* annotations support
* stateful retry, retry state, retry cache
* retry context parameter bag
* retry listeners
* some policies

# Installation

Install package as composer dependency.

```bash
composer require koschos/php-retry
```

# Quick start

Example


You just need to create retry template, which you should is your retry configuration.
```php
// Build retry template with 5 retries and 100 milliseconds waits between them
$retryTemplate = RetryTemplateBuilder::getBuilder()
    ->withMaxAttempts(5)
    ->withBackOffPeriod(100)
    ->build();

// And run your code placed inside callback
$result = $retryTemplate->execute(new class implements RetryCallback {
  public function doWithRetry(RetryContext $context) {
      // dangerous code
  }
});
```
# Features and API

## RetryTemplate

To make processing more robust and less prone to failure, sometimes it helps to automatically retry a failed operation in case it might succeed on a subsequent attempt. 
Errors that are susceptible to this kind of treatment are transient in nature. 
The RetryOperations interface looks like this

```php
interface RetryOperations
{
    public function execute(RetryCallback $retryCallback);

    public function executeWithRecovery(RetryCallback $retryCallback, RecoveryCallback $recoveryCallback);
}
```

The basic callback is a simple interface that allows you to insert some business logic to be retried:

```php
interface RetryCallback
{
    public function doWithRetry(RetryContext $retryContext);
}
```

The callback is executed and if it fails (by throwing an Exception), it will be retried until either it is successful, or the implementation decides to abort. 
There are two execute methods in the RetryOperations interface dealing with callback and recovery when all retry attempts are exhausted.

The simplest general purpose implementation of RetryOperations is RetryTemplate. It could be used like this

```php
$template = new RetryTemplate();

// Retry policy with 30 seconds timeout
$policy = new TimeoutRetryPolicy();
$policy->setTimeout(30000);
$template->setRetryPolicy($policy);

$result = $template->execute($myRetryCallback);
```

In the example we execute a web service call and return the result to the user. If that call fails then it is retried until a timeout is reached.

## RetryContext

The method parameter for the <code>RetryCallback</code> is a <code>RetryContext</code>. Many callbacks will simply ignore the context, but if necessary it can be used to store data for the duration of the iteration.

## RecoveryCallback

When a retry is exhausted the RetryOperations can pass control to a different callback, the RecoveryCallback. 
To use this feature clients have to implement RecoveryCallback interface and call executeWithRecovery method, for example:

```php
$template->executeWithRecovery($myRetryCallback, $myRecoveryCallback);
```

## Retry Policies

Inside a RetryTemplate the decision to retry or fail in the execute method is determined by a <code>RetryPolicy</code> which is also a factory for the RetryContext. 
The RetryTemplate has the responsibility to use the current policy to create a RetryContext and pass that in to the RetryCallback at every attempt. 
After a callback fails the <code>RetryTemplate</code> has to make a call to the RetryPolicy to ask it to update its state (which will be stored in the RetryContext), and then it asks the policy if another attempt can be made. 
If another attempt cannot be made (e.g. a limit is reached or a timeout is detected) then the policy is also responsible for identifying the exhausted state, but not for handling the exception. 
The RetryTemplate will throw the original exception, when no recover is available.

```php
interface RetryPolicy
{
    public function canRetry(RetryContext $context);

    public function open();

    public function close(RetryContext $context);

    public function registerException(RetryContext $context, \Exception $exception);
}
```

Retry provides some simple general purpose implementations of stateless RetryPolicy, for example a <code>SimpleRetryPolicy</code>, and the <code>TimeoutRetryPolicy</code> used in the example above.

The <code>SimpleRetryPolicy</code> just allows a retry on any of a named list of exception types, up to a fixed number of times:

```php
$policy = new SimpleRetryPolicy(5, [\Exception.class, true]);
```

Retry provides next retry policies:
* SimpleRetryPolicy
* TimeoutRetryPolicy
* AlwaysRetryPolicy
* NeverRetryPolicy

## BackOff Policies

When retrying after a transient failure it often helps to wait a bit before trying again, because usually the failure is caused by some problem that will only be resolved by waiting. 
If a <code>RetryCallback</code> fails, the <code>RetryTemplate</code> can pause execution according to the <code>BackOffPolicy</code> in place.

```php
interface BackOffPolicy
{
    public function start(RetryContext $context);

    public function backOff(RetryContext $context);
}
```

Retry provides next backoff policies:
* NoBackOffPolicy
* FixedBackOffPolicy
