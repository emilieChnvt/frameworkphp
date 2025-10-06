<?php

namespace Core\Security;

interface UserInterface
{

    public function getId();

    /**
     * @return mixed
     * Please set one of the properties as an authentication unique info
     * You need to provide one property to search for in the user database
     */
    public function getAuthenticator();
    public function getPassword();

}