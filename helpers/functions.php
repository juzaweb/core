<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

if (! function_exists('dashboard_analytics_chart_enabled')) {
    function dashboard_analytics_chart_enabled(): bool
    {
        return config('analytics.property_id')
            && File::exists(config('analytics.service_account_credentials_json'));
    }
}
