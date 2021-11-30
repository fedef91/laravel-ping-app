<?php
namespace App\Contracts;

interface UserContract {
    /**
    * @param int $id
    * @return mixed
    * @throws ModelNotFoundException
    */
    public function findById(int $id);

    /**
    * @param array $params
    * @return mixed
    */
    public function create(array $params);

    /**
    * @param array $params 
    * @param int $id
    * @return mixed
    */
    public function updateUser(array $params, int $id);

    /**
    * @param $id
    * @return bool
    */
    public function deleteUser(int $id);
}