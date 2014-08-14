<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 04/08/14
 * Time: 22:22
 */

namespace JamesMannion\ForumBundle\Constants;


class Validation {
    /**
     * User
     */
    const REGISTRATION_EMAIL_MATCH = 'Email and Repeated Email must match';
    const REGISTRATION_PASSWORD_MATCH = 'Password and Repeated Password must match';
    const REGISTRATION_EMAIL_UNIQUE = 'Email address is already registered and cannot be registered again';
    const REGISTRATION_USERNAME_UNIQUE = 'Username is already in use and must be unique';
    const REGISTRATION_PASSWORD_MIN_LENGTH = 'Password is not long enough';
    const REGISTRATION_PASSWORD_MAX_LENGTH = 'Password is too long';
    const REGISTRATION_PASSWORD_REQUIRES_LETTERS = 'Password must include at least one letter';
    const REGISTRATION_PASSWORD_REQUIRES_NUMBERS = 'Password must include at least one number';
    const REGISTRATION_PASSWORD_REQUIRES_CASE_DIFFERENCE = 'Password must include both uppercase and lowercase characters
    ';
}