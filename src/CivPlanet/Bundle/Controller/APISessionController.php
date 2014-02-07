<?php

namespace CivPlanet\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class APISessionController extends Controller
{

    public function getSessionAction($id)
    {
        $sessionManager = $this->get('civplanet.session_manager');
        $session = $sessionManager->getSession($id);

        return array('session' => $session);
    }

    /**
     * @QueryParam(name="username", nullable=true)
     * @QueryParam(name="login", nullable=true)
     * @QueryParam(name="logout", nullable=true)
     */
    public function getSessionsAction(ParamFetcher $paramFetcher)
    {
        $params = array();
        foreach ($paramFetcher->all() as $criterionName => $criterionValue) {
            if (isset($criterionValue) && $criterionValue != null) {
                if ($criterionName === 'username') {
                    $params['username'] = $criterionValue;
                } else if ($criterionName === 'login') {
                    $params['login'] = $criterionValue;
                } else if ($criterionName === 'logout') {
                    $params['logout'] = $criterionValue;
                } else if ($criterionName === 'at') {
                    $params['at'] = $criterionValue;
                } else if ($criterionName === 'from') {
                    $params['from'] = $criterionValue;
                } else if ($criterionName === 'to') {
                    $params['to'] = $criterionValue;
                }
            }
        }

        $sessionManager = $this->get('civplanet.session_manager');
        $sessions = $sessionManager->getSessions($params);

        return array('sessions' => $sessions);
    }
}