<?php

use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Exceptions\DuplicateCheckNamesFound;
use Spatie\Health\Exceptions\InvalidCheck;
use Spatie\Health\Facades\Health;

it('can register checks', function () {
    Health::checks([
        UsedDiskSpaceCheck::new(),
    ]);

    expect(Health::registeredChecks())
        ->toHaveCount(1)
        ->and(Health::registeredChecks()[0])
        ->toBeInstanceOf(UsedDiskSpaceCheck::class);
});

it('will throw an exception when duplicate checks are registered', function () {
    Health::checks([
        PingCheck::new(),
        PingCheck::new(),
    ]);
})->throws(DuplicateCheckNamesFound::class);

it('will not throw an exception when all checks have unique names', function () {
    Health::checks([
        PingCheck::new(),
        PingCheck::new()->name('OtherPingCheck'),
    ]);

    expect(Health::registeredChecks())->toHaveCount(2);
});

it('will throw an exception when registering a class that does not exist Check', function () {
    Health::checks([
        new StdClass(),
    ]);
})->throws(InvalidCheck::class);


it('will throw an exception when registering a string', function () {
    Health::checks([
        PingCheck::class,
    ]);
})->throws(InvalidCheck::class);
