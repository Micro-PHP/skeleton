<?php

declare(strict_types=1);

use Micro\Kernel\App\AppKernel;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Configuration\DefaultApplicationConfiguration;
use Dotenv\Dotenv;

$basedir = realpath(__DIR__ . '/../');

require_once $basedir . '/vendor/autoload.php';

return function () use ($basedir) {
    /** @var ApplicationConfigurationInterface $applicationConfiguration */
    $applicationConfiguration = new class($basedir) extends DefaultApplicationConfiguration {

        private readonly Dotenv $dotenv;

        public function __construct(string $basePath)
        {
            $names[] = '.env';
            $env = getenv('APP_ENV') ?: 'dev';
            $names[] = '.env.' .$env;

            $_ENV['BASE_PATH'] = $basePath;

            $this->dotenv = Dotenv::createMutable($basePath, $names, false);
            $this->dotenv->load();

            parent::__construct($_ENV);
        }
    };

    return new AppKernel(
        $applicationConfiguration,
        include $basedir . '/etc/plugins.php',
        $applicationConfiguration->get('APP_ENV', 'dev')
    );
};