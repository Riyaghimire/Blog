<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data =[
            'id'=>$this->id,
            'btag_id'=>$this->btag_id,
            'title'=>$this->title,
            'description'=>$this->description,
            'created_at'=> $this->created_at,
            'updated_at'=>$this->updated_at,
            'image' => isset($this->image)? env("APP_URL")."/image/blogs/".$this->name.$this->image:"",
        ];
        return $data;
    }
}