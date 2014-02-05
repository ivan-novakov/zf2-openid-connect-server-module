<?php

namespace InoOicServer\Oic\User;


/**
 * Represents the user identity.
 */
interface UserInterface
{


    /**
     * @return string
     */
    public function getId();


    /**
     * @param string $id
     */
    public function setId($id);


    /**
     * @return string
     */
    public function getFirstName();


    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);


    /**
     * @return string
     */
    public function getFamilyName();


    /**
     * @param string $familyName
     */
    public function setFamilyName($familyName);


    /**
     * @return string
     */
    public function getEmail();


    /**
     * @param string $email
     */
    public function setEmail($email);
}