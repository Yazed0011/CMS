<?php 
namespace Model\Comment;
use DataBase\DataBase;
use PDO;
use PDOException;

class Comments{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db=new DataBase();
        $this->conn=$this->db->GetConnect();
        if(!$this->conn){
            throw new \Exception('Connect Failed');
        }
    }

    public function Create(array $data){
        try{
            $stmt=$this->conn->prepare("INSERT INTO comments(user_id , content) VALUES(:user_id , :content)");
            $stmt->bindParam(":user_id" , $data['user_id'] , PDO::PARAM_INT);
            $stmt->bindParam("content" , $data['content'] , PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return json_encode([
                    "success" => true,
                    "message" => "Comment Created Successfully"
                ]);
            }
                else {
                    throw new \Exception("Failed to Create Comment", 400);
                }
            }
            catch(PDOException $e){
                error_log($e->getMessage());
                throw new \Exception("ERROR SERVER", 500);
            }

        }
        public function GetAllComment(){
            try{
                $stmt= $this->conn->prepare("SELECT content , created_at , user.name FROM comments 
                INNER JOIN user ON comments.user_id = user.id");
                $stmt->execute();
                $comment=$stmt->fetchall();
                if(count($comment) == 0){
                    return json_encode([
                        "success" => false,
                        "message" => "Not Found"
                    ]);
                }
                return json_encode([
                    "success" =>true,
                    "data" => $comment
                ]);
            }
            catch(PDOException $e){
                error_log($e->getMessage());
                throw new \Exception("ERROR SERVER" , 500);
            }
        }

        public function UpdateComment(array $data){
            try{
                $stmt= $this->conn->prepare("UPDATE comments SET content= :content , user_id= :user_id WHERE id = :id");
                $stmt->bindParam(":content" , $data['content'] , PDO::PARAM_STR);
                $stmt->bindParam(":user_id" , $data['user_id'] , PDO::PARAM_INT);
                $stmt->bindParam(":id" , $data['id'] , PDO::PARAM_INT);
                $stmt->execute();
                if($stmt->rowCount() > 0){
                    return json_encode([
                        "success" => true,
                        "message" => "Comment Update Successfully"
                    ]);
                }
                    else {
                        throw new \Exception("Failed to Update Comment", 400);
                    }
                
            }
            catch(PDOException $e){
                error_log($e->getMessage());
                throw new \Exception("ERROR SERVER" , 500);
            }
        }
}