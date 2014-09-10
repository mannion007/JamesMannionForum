<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 03/03/14
 * Time: 22:02
 */

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Constants\AppConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Constants;

class BaseController extends Controller
{

    protected $appConfig = array();

    public function __construct()
    {
        $this->appConfig['domain']              = AppConfig::DOMAIN;
        $this->appConfig['systemName']          = AppConfig::SYSTEM_NAME;
        $this->appConfig['systemName']          = AppConfig::SYSTEM_NAME;
        $this->appConfig['dateDisplayFormat']   = AppConfig::DATE_DISPLAY_FORMAT;
    }

} 