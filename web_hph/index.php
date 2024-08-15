<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบสมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="mb-4">ระบบสมัครสมาชิก</h4>
                <form action="" method="post">
                    <div class="mb-3 row">
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control"  placeholder="ชื่อ">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-9">
                            <input type="text" name="surname" class="form-control"  placeholder="นามสกุล">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-9">
                            <input type="text" name="username" class="form-control"  placeholder="username">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control"  placeholder="password">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
                        </div>
                    </div>
                </form>
                <hr>
                <p>เป็นสมาชิกอยู่แล้ว? <a href="signin.php">เข้าสู่ระบบ</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+15q8iK6A5ZKeB2eUkvv0zK3z3eOl" crossorigin="anonymous"></script>
</body>
</html>
<?php 
if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['username']) && isset($_POST['password'])) {
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    
    // เชื่อมต่อฐานข้อมูล
    require_once 'connect.php';
    
    // ประกาศตัวแปร
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Hash the password for security

    // Check if the username already exists in the database
    $stmt = $conn->prepare("SELECT id FROM regis_tb WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute(array('username' => $username ));

    if ($stmt->rowCount() > 0) {
        // If username already exists
        echo '<script>
            setTimeout(function(){
                swal({
                    title: "username ซ้ำ!!",
                    text: "กรุณาสมัครใหม่อีกครั้ง",
                    type: "warning"
                }, function(){
                    window.location = "index.php";
                });
            }, 1000);
        </script>';
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO regis_tb (name, surname, username, password) VALUES (:name, :surname, :username, :password)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo '<script>
                setTimeout(function(){
                    swal({
                        title: "สำเร็จ!",
                        text: "สมัครสมาชิกเรียบร้อยแล้ว",
                        type: "success"
                    }, function(){
                        window.location = "signin.php"; // Redirect to login page after success
                    });
                }, 1000);
            </script>';
        } else {
            echo '<script>
                setTimeout(function(){
                    swal({
                        title: "เกิดข้อผิดพลาด",
                        text: "Error!",
                        type: "error"
                    }, function(){
                        window.location = "index.php";
                    });
                }, 1000);
            </script>';
        }
    }


    // Close database connection
    $conn = null;
}
?>