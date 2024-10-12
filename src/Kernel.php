<?php

namespace App;

use App\Context\AbstractContext;
use App\Exception\NotArrayContextConfigException;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        configureContainer as protected parentConfigureContainer;
        configureRoutes as protected parentConfigureRoutes;
    }

    private static array $namespaces = [];

    public const string CONTEXTS_CONF_FILE = 'contexts.php';

    /** @var array<AbstractContext>  */
    protected array $contexts = [];

    /**
     * @throws NotArrayContextConfigException
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadNamespaces();

        $contextsConfigPath = $this->getConfigDir() . '/' . self::CONTEXTS_CONF_FILE;

        if (file_exists($contextsConfigPath) === false) {
            return;
        }

        $contexts = require $contextsConfigPath;

        if (is_array($contexts) === false) {
            throw new NotArrayContextConfigException();
        }

        foreach ($contexts as $context => $isEnabled) {
            if (is_string($context) === true && class_exists($context) && $isEnabled === true) {
                $this->contexts[] = new $context;
            }
        }
    }

    public static function getNamespaceByPath(string $path): ?string
    {
        return self::$namespaces[$path] ?? null;
    }

    /**
     * @throws Context\Exception\Config\NotCallableRoutesConfigException
     * @throws Context\Exception\Config\RoutesConfigNotFoundException
     */
    private function configureRoutes(RoutingConfigurator $configurator): void
    {
        foreach ($this->contexts as $context) {
            $context->initRoutes($configurator);
        }

        $this->parentConfigureRoutes($configurator);
    }

    /**
     * @throws Context\Exception\Config\NotCallableServicesConfigException
     * @throws Context\Exception\Config\ServicesConfigNotFoundException
     */
    private function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $migrations = [];

        foreach ($this->contexts as $context) {
            $context->initServices($container);
            $migrationParams = $context->getRegistryMigrationParams();

            if ($migrationParams !== null) {
                $migrations[trim($migrationParams[0], '\\')] = '/app/' . $migrationParams[1];
            }
        }

        $this->parentConfigureContainer(
            container: $container,
            loader: $loader,
            builder: $builder,
        );

        $builder->prependExtensionConfig(
            name: 'doctrine_migrations',
            config: [
                'migrations_paths' => $migrations,
            ],
        );
    }

    private function loadNamespaces(): void
    {
        $composerFile = dirname(__DIR__) . '/composer.json';

        $composerData = json_decode(file_get_contents($composerFile), true);
        $namespaces = $composerData['autoload']['psr-4'] ?? [];

        self::$namespaces = array_flip($namespaces);
    }
}
