<?php

namespace App\Repositries;

class Repositry implements \App\Http\Interfaces\RepoInterface
{
    protected $model ;
    public function __construct(Model $model){
        $this->model = $model;
    }

    public function index(){
        return $this->model->all(); 
    }
    public function create(array $data){
        return $this->model->create();

    }
    public function update(array $data , $id){

        $record = $this->find($id);
        return $record->update($data);

    }
    public function delete($id){

        return $this->model->destroy($id);


    }
    public function show($id){

        return $this->model->findOrfail($id);

    }

    // eager load database relationships
    public function with($relation)
    {
        return $this->model->with($relation);
    }
}