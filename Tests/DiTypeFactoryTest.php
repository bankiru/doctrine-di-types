<?php

namespace Bankiru\Doctrine\DiType\Tests;

use Bankiru\Doctrine\DiType\DiTypeFactory;
use PHPUnit\Framework\TestCase;

final class DiTypeFactoryTest extends TestCase
{
    /**
     * @expectedException \OutOfBoundsException
     */
    public function testDiTypeFactoryFailsToCreateUnregisteredType()
    {
        $factory = new DiTypeFactory();
        $factory->create('unknown');
    }

    public function testDiTypeFactoryCanOverrideType()
    {
        $factory = new DiTypeFactory();
        $factory->addType('std', \stdClass::class);

        $t1 = $factory->create('std');
        $t2 = $factory->create('std');

        self::assertNotSame($t1, $t2);
    }
}
