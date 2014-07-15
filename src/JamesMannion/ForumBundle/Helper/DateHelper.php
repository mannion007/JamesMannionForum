<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 15/07/14
 * Time: 12:42
 */

namespace JamesMannion\ForumBundle\Helper;

class DateHelper {

    public static function listYearsInRange($fromYearsAgo, $toYearsAgo){
        $startDate = new \DateTime('Now');
        $startDate->sub(
            new \DateInterval('P' . $fromYearsAgo . 'Y')
        );

        $EndDate = new \DateTime('Now');
        $EndDate->sub(
            new \DateInterval('P' . $toYearsAgo . 'Y')
        );

        return range($EndDate->format('Y'), $startDate->format('Y'));

    }

} 