<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Juzaweb\Core\PageBuilder\Elements\Grids\Col12;

class DashboardAnalytis
{
    public function charts($container): void
    {
        $container->add(Col12::make()->attributes(['item' => true, 'xs' => 12, 'sm' => 12, 'md' => 8])
            ->add(Analytic::visitorChart()));

        $container->add(Col12::make()->attributes(['item' => true, 'xs' => 12, 'sm' => 12, 'md' => 4])
            ->add(Analytic::topCountriesChart()));

        $container->add(Col12::make()->attributes(['item' => true, 'xs' => 12, 'sm' => 12, 'md' => 8])
            ->add(Analytic::mostVisitedPagesChart()));

        $container->add(Col12::make()->attributes(['item' => true, 'xs' => 12, 'sm' => 12, 'md' => 4])
            ->add(Analytic::visitorTypesChart()));

        $container->add(Col12::make()->attributes(['item' => true, 'xs' => 12])
            ->add(Analytic::topReferrersChart()));
    }
}
