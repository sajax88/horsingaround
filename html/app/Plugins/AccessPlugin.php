<?php

declare(strict_types=1);

namespace App\Plugins;

use App\Enum\EnumRole;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Component;
use Phalcon\Acl\Enum;
use Phalcon\Acl\Role;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;


class AccessPlugin extends Injectable {

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        $userData = $this->session->get('user');

        // More roles expected in future
        $role = $userData ? EnumRole::User->value : EnumRole::Guest->value;

        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        $acl = $this->getAcl();
        if (!$acl->isComponent($controller)) {
            $dispatcher->forward([
                'controller' => 'error',
                'action'     => 'pageNotFound',
            ]);
            return false;
        }

        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            // For guests
            $dispatcher->forward([
                'controller' => 'auth',
                'action'     => 'index',
            ]);
            $this->session->destroy();
            return false;
        }
        return true;
    }

    protected function getAcl(): AclList {
        if (isset($this->persistent->acl)) {
            return $this->persistent->acl;
        }

        $acl = new AclList();
        $acl->setDefaultAction(Enum::DENY);

        // This is the base role every other role must inherit from
        $guestRole = new Role(
            EnumRole::Guest->value,
            'Not signed in'
        );
        $acl->addRole($guestRole);

        $userRole = new Role(
            EnumRole::User->value,
            'Authorized user'
        );
        $acl->addRole($userRole, $guestRole);

        $this->registerPublicResources($acl, $guestRole);
        $this->registerPrivateResources($acl, $userRole);

        $this->persistent->acl = $acl;
        return $acl;
    }

    private function registerPublicResources(AclList $acl, Role $guestRole) {
        $publicResources = [
            'auth'  => ['index', 'login', 'logout'],
            'error' => ['pageNotFound', 'accessRestricted'],
        ];
        foreach ($publicResources as $resource => $actions) {
            $acl->addComponent(new Component($resource), $actions);
            // Grant access to public areas to guests (and everyone inheriting)
            foreach ($actions as $action) {
                $acl->allow($guestRole->getName(), $resource, $action);
            }
        }
    }

    private function registerPrivateResources(AclList $acl, Role $userRole) {
        $privateResources = [
            'index' => ['index',],
        ];
        foreach ($privateResources as $resource => $actions) {
            $acl->addComponent(new Component($resource), $actions);
        }
        // Grant access to private area to authorized users only
        foreach ($privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow($userRole->getName(), $resource, $action);
            }
        }
    }
}
