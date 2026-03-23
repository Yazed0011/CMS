<?php 
namespace Controllers\Post;
use Model\Post\Posts;
use  Validate\Post\Valid;

class Post{
    private $posts;
    private $post;

    public function Create(array $data){
        $valid=new Valid($data);
        $post=new Posts();
        $post->CreatePost($valid->GetAll());
        $this->posts=$post;
        return json_encode([
            "success" => true,
            "message" => "Post Created Successfully",
        ]);
    }
    public function GetAllPost(){
        $this->posts=new Posts();
        $data = $this->posts->GetAllPost();
        return json_encode([
            "success" => true,
            "message" => "All Posts",
            "data" => $data
        ]);
    }
    public function GetPost(int $postId){
        $this->post=new Posts();
        $this->post->GetPost($postId);
        return json_encode([
            "success" =>true,
            "data" => $this->post 
        ]);
    }
    
}
