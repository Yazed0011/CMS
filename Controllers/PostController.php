<?php 
namespace Controllers\Post;
use Model\Post\Posts;
use Validate\Post\Valid;
use Middleware\Auth\AUTH;
class Post{
    private $posts;
    private $post;
    private $auth;
    private $valid;

    public function Create(array $data){
        $this->auth=new AUTH();
        $this->auth->handle();
        $this->valid=new Valid($data);
        $this->post=new Posts();
        $this->post->CreatePost($this->valid->GetAll());
        return json_encode([
            "success" => true,
            "message" => "Post Created Successfully"
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
        $row=$this->post->GetPost($postId);
        return json_encode([
            "success" =>true,
            "data" => $row
        ]);
    }
    public function UpdatePost(array $data)
    {
        $this->auth = new AUTH();
        $this->auth->handle();
        if (empty($data['id'])) {
            throw new \Exception("Post id is required", 400);
        }
        $this->valid = new Valid($data);
        $this->post = new Posts();
        $payload = $this->valid->GetAll();
        $payload['id'] = (int) $data['id'];
        $this->post->UpdatePost($payload);
        return json_encode([
            "success" => true,
            "message" => "Update Post Successfully"
        ]);
    }

    public function DeletePost(int $postId)
    {
        $this->auth = new AUTH();
        $user = $this->auth->handle();
        if ($user['is_admin'] !== 1) {
            return json_encode([
                "success" => false,
                "message" => "You Don't Have Access"
            ]);
        }
        $this->post = new Posts();
        $this->post->DeletePost($postId);
        return json_encode([
            "success" => true,
            "message" => "Delete Post Successfully"
        ]);
    }
}
