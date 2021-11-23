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
    * @return mixed
    */
    public function update(array $params);

    /**
    * @param $id
    * @return bool
    */
    public function delete($id);
}