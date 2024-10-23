<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle;

use SonsOfPHP\Bundle\FeatureToggleBundle\Attribute\AsFeature;
use SonsOfPHP\Bundle\FeatureToggleBundle\DependencyInjection\Compiler\FeaturePass;
use SonsOfPHP\Component\FeatureToggle\Feature;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SonsOfPHPFeatureToggleBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        /**
         * @todo provider: sons_of_php.feature_toggle.provider
         * features:
         *   key:
         *     toggle: sons_of_php.feature_toggle.toggle.enabled
         *   another_key:
         *     toggle: sons_of_php.feature_toggle.toggle.disabled
         */
        $definition->rootNode()->children()
            // @todo
            //->scalarNode('provider')
            //    ->defaultValue('sons_of_php.feature_toggle.provider')
            //->end() // provider
            ->arrayNode('features')
                ->info('Features contains a list of features by "key" and the toggle the feature uses')
                ->useAttributeAsKey('key')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('toggle')
                            ->info('Can be "enabled", "disabled", or a service')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('sons_of_php.feature_toggle.toggle.enabled')
                        ->end()
                    ->end()
                ->end()
            ->end() // features
        ->end();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(AsFeature::class)->addTag('sons_of_php.feature_toggle.feature');
        $container->addCompilerPass(new FeaturePass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        foreach ($config['features'] as $key => $value) {
            $value['toggle'] = match ($value['toggle']) {
                'enabled'  => 'sons_of_php.feature_toggle.toggle.enabled',
                'disabled' => 'sons_of_php.feature_toggle.toggle.disabled',
                default    => $value['toggle'],
            };

            $feature = $container->services()->set('sons_of_php.feature_toggle.feature.' . $key, Feature::class);
            $feature->arg(0, $key);
            $feature->arg(1, new Reference($value['toggle']));
            $feature->tag('sons_of_php.feature_toggle.feature');
        }
    }
}
