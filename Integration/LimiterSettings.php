<?php

/*
 * @copyright   2020 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticLimiterBundle\Integration;

use Doctrine\DBAL\Connection;
use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\CoreBundle\Helper\CoreParametersHelper;

class LimiterSettings
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $routers;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $style;

    /**
     * @var bool|int
     */
    private $numberOfContacts;

    /**
     * LimiterSettings constructor.
     *
     * @param CoreParametersHelper $coreParametersHelper
     * @param Connection           $connection
     */
    public function __construct(CoreParametersHelper $coreParametersHelper, Connection $connection)
    {
        $limiter          = $coreParametersHelper->getParameter('limiter', []);
        $this->limit      = (int) ArrayHelper::getValue('limit', $limiter, 0);
        $this->message    = (string) ArrayHelper::getValue('message', $limiter, '');
        $this->style      = (string) ArrayHelper::getValue('style', $limiter, '');
        $this->routers    = (array) ArrayHelper::getValue('routes', $limiter, []);
        $this->enabled    = $this->limit > 0 ? true : false;
        $this->connection = $connection;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return (int) $this->limit;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return (array) $this->routers;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return str_replace(
            ['{numberOfContacts}', '{actualLimit}'],
            [$this->getNumberOfContacts(), $this->limit],
            $this->message
        );

    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    public function isLimitedAccount()
    {
        if ($this->enabled && $this->limit > 0) {

            $contacts = $this->getNumberOfContacts();
            if ($contacts > $this->limit) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool|int
     */
    public function getNumberOfContacts()
    {
        if ($this->numberOfContacts === null) {
            $qb                     = $this->connection->createQueryBuilder();
            $this->numberOfContacts = (int) $qb->select('count(l.id)')
                ->from(MAUTIC_TABLE_PREFIX.'leads', 'l')
                ->where($qb->expr()->isNotNull('l.date_identified'))
                ->execute()
                ->fetchColumn();
        }
        return $this->numberOfContacts;
    }

}
