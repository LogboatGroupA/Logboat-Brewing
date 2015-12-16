<?php require '../utilities/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Logboat Brewing</title>
    <?php require '../utilities/links.php'; ?>
    
    <script>
        $(document).ready(function() {
            $("#errorMessage").hide();
            $("#successMessage").hide();
            
            $("#createForm").submit(function(e) {
                e.preventDefault();
                
                $.post("../api/user/create.php", $("#createForm").serialize(), function(jsonData) {
                    
                    if(jsonData.success === false){
                        $("#errorMessage")
                            .html(jsonData.error)
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                        return;
                    }
                    
                    var successText = "User created successfully. This is their temporary password: " + jsonData.result;
                    
                    $("#successMessage")
                            .html(successText)
                            .slideDown("fast");
                });
            });
        });
    </script>
    
    <style>
        #successMessage {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php require '../navbar.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form id="createForm">
                    <h2 class="text-center">New User</h2>
                    <div id="errorMessage" class="alert alert-danger text-center" role="alert"></div>
                    
                    <!-- Username -->
                    <div class="form-group">
                        <label for='username' class="sr-only">Username</label>
                        <input type="text" id="username" name="username" class="form-control input-lg" placeholder="Username" required autofocus>
                    </div>
                    
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
                    
                    <div id="successMessage" class="alert alert-info text-center" role="alert"></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>