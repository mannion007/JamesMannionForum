<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 15/07/14
 * Time: 12:37
 */

namespace JamesMannion\ForumBundle\Constants;


class AppConfig {
    const DOMAIN = 'jamesmannionforum.dev';
    const SYSTEM_NAME = 'My Lovely Forum';
    const ADMIN_EMAIL = 'james@mannionking.com';
    const MINIMUM_AGE = 18;
    const MAXIMUM_AGE = 100;

    const BUILDINGS_PER_PAGE = 10;
    const ROOMS_PER_PAGE = 10;
    const THREADS_PER_PAGE = 10;

    const PASSWORD_MIN_LENGTH = 8;
    const PASSWORD_MAX_LENGTH = 20;
    const PASSWORD_REQUIRES_LETTERS = true;
    const PASSWORD_REQUIRES_CASE_DIFFERENCE = true;
    const PASSWORD_REQUIRES_NUMBERS = true;
}