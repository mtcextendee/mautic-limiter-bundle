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
    public function generate()
    {
        $routes = $this->limiterSettings->getRoutes();
        $js     = <<<JS
            Mautic.generateLimiter = function(){
            {$this->generateJs($routes)}
            }
            setTimeout(function () {
            Mautic.generateLimiter();
        }, 50);
            
JS;
        return $this->generateHeader().$js;

    }


    /**
     * @param $section
     * @param $route
     *
     * @return string
     */
    private function generateJs($routes)
    {
        $js = '';
        foreach ($routes as $route) {
            $js .= <<<JS
    Mautic.generateLimiterMesssage('{$route}');   
JS;
        }

        return $js;

    }

    /**
     * @return string
     */
    private function generateHeader()
    {

        $js = <<<JS
        Mautic.generatePageTitleBackup = Mautic.generatePageTitle;
        Mautic.generatePageTitle = function(route){
            Mautic.generatePageTitleBackup(route);
            Mautic.generateLimiter();
        }
        Mautic.matchRuleShort =  function (str, rule) {
          var escapeRegex = (str) => str.replace(/([.*+?^=!:$()|\[\]\/\\\\])/g, "\\\\$1");
          return new RegExp("^" + rule.split("*").map(escapeRegex).join(".*") + "$").test(str);
        }
        
        Mautic.generateLimiterMesssage = function (rule) {
          var str = location.href;
          console.log(str);
          console.log(rule);
        var position = Mautic.matchRuleShort(str,  rule);
        console.log(position);
        if (position === true) {
            mQuery('.modal').hide().prev().hide();
            mQuery('.page-header .btn-group').remove()
            mQuery('.content-body .panel-body, .content-body .page-list').remove()
    mQuery('form .box-layout,.content-body .panel').html('<div class="row"><div class="alert alert-warning alert-limiter-custom col-md-6 col-md-offset-3 mt-md" style="white-space: normal;"><p>{$this->limiterSettings->getMessage(
        )}</p></div></div>');
       };
        }
        
JS;

        return $js;
    }


}
