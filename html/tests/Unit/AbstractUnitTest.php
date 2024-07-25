<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Di\Di;
use Phalcon\Di\FactoryDefault;
use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\TestCase;

/**
 * Official Phalcon doc suggests using incubator-test lib,
 * but since it does not support Phalcon > 4.0, we use our own abstract class instead
 */
abstract class AbstractUnitTest extends TestCase {
    private bool $loaded = false;

    /**
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        parent::setUp();

        $di = new FactoryDefault();
        Di::setDefault($di);

        $this->loaded = true;
    }

    /**
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        Di::reset();
        parent::tearDown();
    }

    public function __destruct() {
        if (!$this->loaded) {
            throw new IncompleteTestError(
                "Please run parent::setUp() to reset DI and do other useful things."
            );
        }
    }
}
