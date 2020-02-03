<?php

/*
 * @copyright   2020 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticLimiterBundle\Service;

use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticLimiterBundle\Integration\LimiterSettings;

class Limiter
{
    /**
     * @var LimiterSettings
     */
    private $limiterSettings;

    /**
     * @var LimiterJs
     */
    private $limiterJs;

    /**
     * Limiter constructor.
     *
     * @param LimiterSettings $limiterSettings
     * @param LimiterJs       $limiterJs
     */
    public function __construct(LimiterSettings $limiterSettings, LimiterJs $limiterJs)
    {
        $this->limiterSettings = $limiterSettings;
        $this->limiterJs       = $limiterJs;
    }

    /**
     * @return string
     */
    public function getJs()
    {
        if ($this->limiterSettings->isLimitedAccount()) {
            $routes = $this->limiterSettings->getRoutes();

            return $this->limiterJs->generate($routes);
        }
    }

    /**
     * @return string
     */
    public function getCss()
    {
        return $this->limiterSettings->getStyle();
    }
}
