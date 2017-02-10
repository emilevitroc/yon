<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Yon\Bundle\UserBundle\YonUserBundle(),
            new Yon\Bundle\PrivilegeBundle\YonPrivilegeBundle(),
            new Yon\Bundle\ParisBundle\YonParisBundle(),
            new Endroid\Bundle\TwitterBundle\EndroidTwitterBundle(),
            new Yon\Bundle\BadgeBundle\YonBadgeBundle(),
            new Yon\Bundle\RankBundle\YonRankBundle(),
            new Yon\Bundle\MessageBundle\YonMessageBundle(),
        );
        $bundles[] = new Circle\RestClientBundle\CircleRestClientBundle();
        $bundles[] = new FOS\JsRoutingBundle\FOSJsRoutingBundle();

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        date_default_timezone_set( 'Europe/Paris' );
//        date_default_timezone_set('UTC');
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
