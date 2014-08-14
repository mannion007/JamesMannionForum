<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 15/07/14
 * Time: 12:05
 */

namespace JamesMannion\ForumBundle\Constants;


class Label {

    /*
     * Room
     */
    const ROOM_NAME         = 'Room Name';
    const ROOM_DESCRIPTION  = 'Room Description';

    /*
     * Thread
     */
    const THREAD_TITLE  = 'Thread Title';
    const THREAD_AUTHOR = 'Thread Author';
    const THREAD_ROOM   = 'Thread Room';

    /*
     * Post
     */
    const POST_BODY     = 'Post Body';
    const POST_AUTHOR   = 'Post Author';
    const POST_THREAD   = 'Post Thread';

    /*
     * User
     */
    const REGISTRATION_USERNAME             = 'Username';
    const REGISTRATION_EMAIL                = 'Email';
    const REGISTRATION_REPEAT_EMAIL         = 'Repeat Email';
    const REGISTRATION_PASSWORD             = 'Password';
    const REGISTRATION_REPEAT_PASSWORD      = 'Repeat Password';
    const REGISTRATION_MEMORABLE_QUESTION   = 'Memorable Question';
    const REGISTRATION_MEMORABLE_ANSWER     = 'Answer to Memorable Question';

    /*
     * Authentication
     */
    const AUTHENTICATION_USER_USERNAME  = 'Username';
    const AUTHENTICATION_USER_PASSWORD = 'Password';
}