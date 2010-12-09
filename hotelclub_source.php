<?php
/**
 * HotelClub
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.txt. It is also available through the
 * world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@hotelclub.com so
 * we can send you a copy immediately.
 *
 * @category   HotelClub
 * @package    HotelclubSource
 * @subpackage PHP
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 * @version    SVN: $Id$
 */

/**
 * @see HotelClub
 */
App::import('Vendor', 'HotelClub', array('file' => 'HotelClub.php'));

/**
 * HotelClub DataSource class
 *
 * The HotelclubSource class is a CakePHP DataSource for interfacing with
 * HotelClub XML Web Services.
 *
 * @category   HotelClub
 * @package    HotelclubSource
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 */

class HotelclubSource extends DataSource
{
    /**
     * Constructor
     *
     * @param  array $config The DataSource configuration options.
     * @return void
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * Query
     *
     * @return mixed
     */
    function query()
    {
        $service = null;
        $method = null;
        $queryData = null;
        $args = func_get_args();

        if (count($args) >= 2) {
            $method = ucfirst($args[0]);
            $queryData = $args[1];
        }

        if (!$method || !$queryData) {
            return false;
        }

        switch ($method) {
            case 'HotelAvailabilityRequest':
            case 'HotelSearchRequest':
                $service = 'Availability';
                break;
            case 'CityListRequest':
            case 'CountryListRequest':
            case 'HotelImageRequest':
            case 'HotelInfoRequest':
            case 'HotelListRequest':
            case 'HotelSuburbListRequest':
            case 'MonthlyFavouriteCityListRequest':
            case 'TopCityListRequest':
                $service = 'Content';
                break;
            case 'BookingStatusRequest':
            case 'HotelBookingRequest':
            case 'HotelRateRuleRequest':
                $service = 'Reservation';
                break;
        }

        if (!$service) {
            return false;
        }

        $hotelClub = new HotelClub();
        foreach ($this->config as $key => $value) {
            $hotelClub->$service->Config->$key = $value;
        }
        return $hotelClub->$service->$method($queryData[0]);
    }
}