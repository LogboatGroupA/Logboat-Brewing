<?php require '../utilities/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Logboat Brewing</title>
    <?php require '../utilities/links.php'; ?>
    <link rel="stylesheet" href="../styles.css"/>
    <script>
        $(document).ready(function() {
            updateUserList();
        });
        
        function updateUserList() {
            $.getJSON("<?= getBaseUrl(); ?>api/user/getAll.php", function(data) {
                if(!data.success) {
                    console.error(data.error);
                    //TODO: handle error in UI
                    return;
                }
                
                var userList = $("#userList");
                userList.empty();
                
                data.result.forEach(function(user) {
                    var userCell = $("<div></div>");
                    userCell.addClass('cell-basic clearfix');
                    
                    /**
                     * Name and activation date
                     */
                    var leftDiv = $("<div></div>");
                    leftDiv.addClass("inline");
                    
                    var name = $("<h3><a href='user.php?id=" + user.id + "'>"
                            + user.username + "</a></h3>");
                    name.addClass("inline-block no-spacing");
                    
                    var createdDate = $("<h5></h5>");
                    var activeSinceText = "Active since " + 
                            new Date(Date.parse(user.created)).toDateString();
                    
                    createdDate.text(activeSinceText);
                    createdDate.addClass("inline-block sub-header");
                    
                    leftDiv.append(name);
                    leftDiv.append("<br>");
                    leftDiv.append(createdDate);
                    
                    userCell.append(leftDiv);
                    
                    /**
                     * Delete button
                     */
                    if(user.id !== <?= $_SESSION['userId']; ?>) {
                        var deleteButton = $("<button></button>");
                        deleteButton.addClass("btn btn-md btn-danger center-vertical right");
                        deleteButton.text("Delete");
                        deleteButton.click(function() {
                            deleteUser(user.id, user.username);
                        });
                    
                        userCell.append(deleteButton);
                    }
                    
                    userList.append(userCell);
                });
            });
            
            function deleteUser(id, username) {
                if(confirm("Are you sure you want to delete " + username + " from the database?")) {
                    $.post("<?= getBaseUrl(); ?>api/user/delete.php", { id: id }, function(data) {
                        /*var jsonData = $.parseJSON(data);
                        if(!jsonData.success) {
                            console.error(jsonData.error);
                            //Handle error in UI
                            return;
                        }*/
                        
                        console.log(data);
                        
                        updateUserList();
                    });
                }
            }
        }
    </script>
    <style>
        #newUser {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php require '../navbar.php'; ?>
    <div class="container">
        <h2 class="text-center">Users</h2>
        <a id="newUser" role="button" class="btn btn-lg btn-primary center-horizontal" href="create.php">New User</a>
        
        <div class="row">
            <div id="userList" class="col-md-10 col-md-offset-1">
                
            </div>
        </div>
    </div>
</body>
</html>