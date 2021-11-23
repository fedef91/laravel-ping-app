<?php

use App\Models\User;
use App\Contracts\UserContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class UserRepository extends BaseRepository implements UserContract
{
    public function __construct(User $model){
        parent::__construct($model);
        $this->model = $model;
    }
    /**
    * @param int $id
    * @return mixed
    * @throws ModelNotFoundException
    */
    public function findById(int $id){
        try {
            return $this->findOneOrFail($id);
        } 
        catch (ModelNotFoundException $e) {
             throw new ModelNotFoundException($e);
        }
    }
            
    /**
    * @param array $params
    * @return Category|mixed
    */
    public function create(array $params){
        try {
            //hash password
            $collection = collect($params)->except("_token");
            $data = new User($collection->all());
            $data->save();
            return $data;
        } 
        catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
    * @param array $params
    * @return mixed
    */
    public function update(array $params){
        $data = $this->findById($params["id"]);
        $collection = collect($params)->except("_token");
        $data->update($collection->all());
        return $data;
    }

    /**
    * @param $id
    * @return bool|mixed
    */
    public function delete($id){
        $data = $this->findById($id);
        $data->delete();
        return $data;
    }
}