<?php 
namespace  Validate\Post;

class Valid{
    private $title;
    private $descrption;
    private $created_at;
    private $user_id;
    private $category;

    public function __construct(array $data)
    {
    
    
        $errors = [];
        if (empty($data['title']) || trim($data['title']) == "") {
            $errors[] = "Title Is Required";
        }
        if (empty($data['descrption']) || trim($data['descrption']) == "") {
            $errors[] = "Description Is Required";
        }
        if (empty($data['created_at']) || trim($data['created_at']) == "") {
            $errors[] = "Created At Is Required";
        }
        if (empty($data['user_id']) || trim($data['user_id']) == "") {
            $errors[] = "User Id Is Required";
        }
        if (empty($data['category']) || trim($data['category']) == "") {
            $errors[] = "Category Is Required";
        }
        if (empty($data['status']) || trim($data['status']) == "") {
            $errors[] = "Status Is Required";
        }
        if (!empty($errors)) {
            throw new \Exception(implode(", ", $errors), 400);
        }
        
        $this->title = strip_tags($data['title']);
        $this->descrption = strip_tags($data['descrption']);
        $this->created_at = $data['created_at'];
        $this->user_id =(int) $data['user_id'];
        $this->category= strip_tags($data['category']);
    }
    public function GetTitle(){
        return $this->title;
    }
    public function GetDescrption(){
        return $this->descrption;
    }
    public function GetCreateAt(){
        return $this->created_at;
    }
    public function GetUserId(){
        return $this->user_id;
    }
    public function GetCategory(){
        return $this->category;
    }
    public function GetAll(){
        return [
            'title' =>$this->title,
            'descrption'=>$this->descrption , 
            'created_at'=>$this->created_at,
            'user_id' =>$this->user_id ,
            'category'=> $this->category
        ];
    }
}