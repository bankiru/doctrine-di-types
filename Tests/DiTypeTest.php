<?php

namespace Bankiru\Doctrine\DiType;

use Bankiru\Doctrine\DiType\Test\ContainerTestTrait;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

final class DiTypeTest extends TestCase
{
    use ContainerTestTrait;

    public function testDiTypeIsRegistered()
    {
        $container = $this->getContainer();

        $container->register('test_type', TestDiType::class)
                  ->addMethodCall('setContainer', [new Reference('service_container')])
                  ->addTag('doctrine_di_type', ['type' => 'test']);

        $this->compile($container);

        $container->get('doctrine');

        $types = Type::getTypesMap();

        self::assertArrayHasKey('test', $types);

        /** @var TestDiType $type */
        $type = Type::getType('test');

        self::assertInstanceOf(TestDiType::class, $type);
        self::assertSame($container, $type->getContainer());
    }

    /**
     * @expectedException \LogicException
     */
    public function testDiRequiresTypeTagAttribute()
    {
        $container = $this->getContainer();

        $container->register('test_type', TestDiType::class)
                  ->addMethodCall('setContainer', [new Reference('service_container')])
                  ->addTag('doctrine_di_type');

        $this->compile($container);
    }

    public function testTypesAreNotRegisteredUntilRequired()
    {
        $container = $this->buildContainer(
            [
                new DoctrineBundle(),
                new BankiruDoctrineDiTypeBundle(),
            ],
            [
                'doctrine_di_types' => [
                    'init_on_boot' => false,
                ],
                'doctrine'          => [
                    'dbal' => [
                        'driver' => 'pdo_sqlite',
                        'memory' => true,
                    ],
                ],

            ],
            false
        );

        $container->register('test_type', TestDiType::class)
                  ->addMethodCall('setContainer', [new Reference('service_container')])
                  ->addTag('doctrine_di_type', ['type' => 'test']);

        $this->compile($container);

        self::assertArrayNotHasKey('test', Type::getTypesMap());

        $container->get('doctrine');

        self::assertArrayHasKey('test', Type::getTypesMap());
    }

    public function testExtensionShouldBeEnabled()
    {
        $container = $this->buildContainer(
            [
                new DoctrineBundle(),
                new BankiruDoctrineDiTypeBundle(),
            ],
            [
                'doctrine' => [
                    'dbal' => [
                        'driver' => 'pdo_sqlite',
                        'memory' => true,
                    ],
                ],

            ],
            false
        );

        $this->compile($container);

        self::assertFalse($container->has('bankiru_doctrine_di_types.type_factory'));
    }

    public function testBundlePreservesPreviousConfigurator()
    {
        $container = $this->getContainer();

        $container->getDefinition('doctrine')
                  ->setConfigurator([Configurator::class, 'configure']);

        $this->compile($container);
    }

    /**
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        $container = $this->buildContainer(
            [
                new DoctrineBundle(),
                new BankiruDoctrineDiTypeBundle(),
            ],
            [
                'doctrine_di_types' => null,
                'doctrine'          => [
                    'dbal' => [
                        'driver' => 'pdo_sqlite',
                        'memory' => true,
                    ],
                ],

            ],
            false
        );

        return $container;
    }
}

final class Configurator
{
    public static function configure($instance)
    {
        Assert::assertInstanceOf(ManagerRegistry::class, $instance);
    }
}

final class TestDiType extends AbstractDiType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** {@inheritdoc} */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'test';
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->getOriginal()->container;
    }
}
