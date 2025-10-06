<?php

namespace Core\Security;

use Core\Session\Session;

abstract class UserManagement implements UserInterface
{

    protected int $id;
    protected string $password;

    private function encryptPassword(string $clearPassword) : string
    {
        return password_hash($clearPassword, PASSWORD_DEFAULT);
    }

    public function setPassword ($clearPassword) : void
    {
        $this->password = $this->encryptPassword($clearPassword);
    }



    public function passwordMatches($clearPassword) : bool
    {
        return password_verify($clearPassword, $this->password);
    }

    public function logIn(string $clearPassword) : bool
    {
       if( $this->passwordMatches($clearPassword) )
       {
           Session::set("user", [
               "id" => $this->id,
               "authenticator"=>$this->getAuthenticator()
           ]);
           return true;
       }
       return false;
    }

    public function logOut()
    {
        Session::remove("user");
    }



}