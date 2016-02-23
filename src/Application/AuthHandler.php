<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 22/02/2016
 * Time: 22:00
 */

namespace Application;

use Application\Exception\AuthFailedException;
use Framework\KernelEvent;
use Framework\Routing\UrlMatcherInterface;
use Framework\Session\SessionInterface;

class AuthHandler
{
    /**
     * @var UrlMatcherInterface
     */
    private $router;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * AuthHandler constructor.
     * @param UrlMatcherInterface $router
     * @param SessionInterface $session
     */
    public function __construct(UrlMatcherInterface $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param KernelEvent $event
     * @return bool
     * @throws AuthFailedException
     */
    public function onKernelRequest(KernelEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->getAttribute('_route');
        if(!in_array($route,['signin','home','signup'])){
            return $this->guest(true);
        }
        if($route == 'dashboard'){
            return $this->guest();
        }
    }

    /**
     * Verify if the guest is logged
     * @param bool $withPerms
     * @return bool
     * @throws AuthFailedException
     */
    public function guest($withPerms = false)
    {
        $auth = $this->session->fetch('auth');
        //if he don't need perms
        if($auth && !$withPerms){
            return true;
        }
        //if we have perms
        if($auth && $withPerms){

            if(!isset($auth['perms'])){
                throw new AuthFailedException('Vous n\'avez pas les permitions requises');
            }
            if(!$this->verifyPerms($auth['perms'])){
                throw new AuthFailedException('Vous n\'avez pas les permitions requises');
            }
            return true;
        }
        throw new AuthFailedException('Vous devez être connecté');
    }

    /**
     * Search label AUTHOR or ADMIN
     * @param array $perms
     * @return bool
     */
    private function verifyPerms(array $perms)
    {
        foreach($perms as $perm){
            if($perm['label'] == "AUTHOR" || $perm['label'] == "ADMIN"){
                return true;
            }
        }
        return false;
    }
}