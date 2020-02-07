<?php

/*
 * @copyright   2020 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticLimiterBundle\Controller\Api;

use FOS\RestBundle\Util\Codes;
use Mautic\ApiBundle\Controller\CommonApiController;
use Mautic\CoreBundle\Helper\ArrayHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class LimiterApiController extends CommonApiController
{
    /**
     * @var \Mautic\CoreBundle\Configurator\Configurator $configurator
     */
    private $configurator;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param FilterControllerEvent $event
     */
    public function initialize(FilterControllerEvent $event)
    {
        $this->configurator = $this->get('mautic.configurator');
        $this->filesystem = $this->get('symfony.filesystem');

    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessageAction()
    {
        return $this->getViewFromLimiter('message');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getLimitAction()
    {
        return $this->getViewFromLimiter('limit');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRoutesAction()
    {
        return $this->getViewFromLimiter('routes');
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStyleAction()
    {
        return $this->getViewFromLimiter('style');
    }

    /**
     * @param $key
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getViewFromLimiter($key)
    {
        $limiter = $this->coreParametersHelper->getParameter('limiter');
        $view    = $this->view(['response' => ArrayHelper::getValue($key, $limiter)]);

        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction()
    {
        $limiter = $this->coreParametersHelper->getParameter('limiter');
        $view    = $this->view(['response' => $limiter], Codes::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateMessageAction()
    {
        return $this->processUpdate('message');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateLimitAction()
    {
        return $this->processUpdate('limit');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateRoutesAction()
    {
        return $this->processUpdate('routes');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateStyleAction()
    {
        return $this->processUpdate('style');
    }

    /**
     * @param $key
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function processUpdate($key)
    {
        $all   = $this->request->request->all();
        $value = ArrayHelper::getValue($key, $all);
        if ($value === null) {
            $view = $this->view(['error' => sprintf("Parameter %s not found", $key)], Codes::HTTP_OK);;

            return $this->handleView($view);
        }
        $limiter       = $this->coreParametersHelper->getParameter('limiter');
        $limiter[$key] = $value;
        $toUpdate      = ['limiter' => $limiter];
        $this->configurator->mergeParameters($toUpdate);
        $this->configurator->write();
        $this->filesystem->remove($this->coreParametersHelper->getParameter('kernel.cache_dir'));
        $view = $this->view(['success' => '1'], Codes::HTTP_OK);

        return $this->handleView($view);
    }
}
