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
use Symfony\Component\Routing\RouterInterface;

class LimiterJs
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var LimiterSettings
     */
    private $limiterSettings;

    /**
     * LimiterJs constructor.
     *
     * @param RouterInterface $router
     * @param LimiterSettings $limiterSettings
     */
    public function __construct(RouterInterface $router, LimiterSettings $limiterSettings)
    {
        $this->router          = $router;
        $this->limiterSettings = $limiterSettings;
    }

    /**
     * @param string $routes
     *
     * @return string
     */
    public function generate($routes)
    {
        $js = '';
        $header = $this->generateHeader();
        foreach ($routes as $name => $parameters) {
            $route   = $this->router->generate($name, $parameters);
            $actions = explode('_', $name);
            if (isset($actions[1])) {
                $js.= $this->generateJs($actions[1], $route);
            }
        }

        if ($js) {
            return $header.$js;
        }

    }


    /**
     * @param $action
     * @param $route
     *
     * @return string
     */
    private function generateJs($action, $route)
    {
        $action = str_replace(['contact'], ['lead'], $action);
        $actionOnLoad       = $action.'OnLoad';
        $actionOnLoadBackup = $actionOnLoad.'Backup';

        $js = <<<JS
            Mautic.{$actionOnLoadBackup}= Mautic.{$actionOnLoad};
Mautic.{$actionOnLoad} = function(container, response){
    Mautic.{$actionOnLoadBackup}(container, response);
    Mautic.generateLimiterMesssage('{$route}');   
    };
JS;

        return $js;

    }

    /**
     * @return string
     */
    private function generateHeader()
    {
        $js = <<<JS
        Mautic.generateLimiterMesssage = function (route) {
          var str = location.href;
          console.log(str);
          console.log(route);
        var position = str.search(route);
        if (position > -1) {
            mQuery('.modal').hide().prev().hide();
            mQuery('.page-header .btn-group').remove()
    mQuery('form .box-layout').html('<div class="row"><div class="alert alert-warning alert-limiter-custom col-md-6 col-md-offset-3 mt-md" style="white-space: normal;"><p>{$this->limiterSettings->getMessage()}</p></div></div>');
       };
        }
        
JS;

        return $js;
    }


}
