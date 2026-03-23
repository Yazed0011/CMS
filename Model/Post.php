<?php 
namespace Model\Post;
use DataBase\DataBase;
use Exception;
use PDO;
use PDOException;

class Posts{
    private $db;
    private $conn;
    public $posts;
    public $post;

    public function __construct()
    {
        $this->db=new DataBase();
        $this->conn=$this->db->GetConnect();
        if(!$this->conn){
            throw new Exception('Connect Failed');
        }
    }
    public function CreatePost(array $data){
        try {
            $stmt = $this->conn->prepare("INSERT INTO posts (title, descrption, created_at, user_id, category) VALUES (:title, :descrption, :created_at, :user_id, :category)");
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':descrption', $data['descrption'], PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $data['created_at'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':category', $data['category'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "message" => "Post Created Successfully"];
            }
            else {
                throw new \Exception("Failed to Create Post", 400);
            }
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }

    public function GetAllPost(){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM posts");
            $stmt->execute();
            $this->posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$this->posts) {
                throw new \Exception("No Posts Found", 404);
            }
            return $this->posts;
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
    public function GetPost(int $PostId){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = :id");
            $stmt->bindParam(':id', $PostId, PDO::PARAM_INT);
            $stmt->execute();
            $this->post = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->post;
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
    public function UpdatePost(array $data){
        try {
            $stmt = $this->conn->prepare("UPDATE posts SET title = :title, descrption = :descrption, created_at = :created_at, user_id = :user_id, category = :category WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':descrption', $data['descrption'], PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $data['created_at'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':category', $data['category'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "message" => "Post Updated Successfully"];
            }
            else {
                throw new \Exception("Failed to Update Post", 400);
            }
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
    public function DeletePost(int $PostId){
        try {
            $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :id");
            $stmt->bindParam(':id', $PostId, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "message" => "Post Deleted Successfully"];
            }
        }
        catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
}