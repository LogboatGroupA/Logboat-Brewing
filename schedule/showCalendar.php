<?php
require '../utilities/init.php';
require '../utilities/tools.php';

if(!isLoggedIn()) {
    header("Location: " . getBaseUrl() . "user/login.php");
    exit();
}
?>
<html>
    <head>
        <title>Logboat Brewing</title>
        <?php require '../utilities/links.php'; ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.6/jstz.js"></script>-->
        <!--<script src="https://cdn.jsdelivr.net/bootstrap.calendar/0.2.4/js/calendar.min.js"></script>-->
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.calendar/0.2.4/css/calendar.min.css">-->
        
        <script>
            function clickEvent(calEvent) {
                if(calEvent.editable === true) {
                    $("#updateModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                    $("#updateModal").modal("toggle");
                    $.get("updateModal.php", {"brewId": calEvent.id}, function(data) {
                        $("#updateModal .modal-body div").fadeOut(400, function() {
                            $("#updateModal .modal-body").hide().html(data).slideDown("slow");
                            
                            //Datepicker Options
                            $(".datepicker").datetimepicker({
                                showTodayButton: true,
                                showClose: true
                            });
                            
                            //Set event dates
                            $("#startDatepicker").data("DateTimePicker").date(new Date($("#startDatepicker").attr("data-givenDate")));
                            $("#endDatepicker").data("DateTimePicker").date(new Date($("#endDatepicker").attr("data-givenDate")));
                            //Datepicker Validation
                            $("#startDatepicker").on("dp.change", function(e) {
                                if($("#startDatepicker input").val() != "") {
                                    $("#endDatepicker").data("DateTimePicker").enable();
                                    $("#endDatepicker").data("DateTimePicker").minDate($("#startDatepicker").data("DateTimePicker").date());
                                } else {
                                    $("#endDatepicker input").val("");
                                    $("#endDatepicker").data("DateTimePicker").disable();
                                }
                            });
                            
                            //Set an event to empty the modal when it closes
                            $("#updateModal").on("hidden.bs.modal", function(e) {
                                $("#updateModal .modal-body").empty();
                            });
                        });
                    });
                }
            }
        
            $(document).ready(function() {
                $("#calendar").fullCalendar({
                    header: {
        				left: 'prev,next today',
        				center: 'title',
        				right: 'month,basicWeek,agendaDay'
        			},
        			slotEventOverlap: false,
                    events: "../api/schedule/getAll.php",
                    eventClick: clickEvent
                });
                
                //Custom functions
                
                //Saves current calendar data to database ... Needs Revamped to prevent overwrites and such!! Also, extreme database usage. Should trim down....later
                $("#calendarSave").click(function() {
                    //console.log(JSON.stringify($("#calendar").fullCalendar('clientEvents')));
                    $.post("../api/schedule/saveFullCalendar.php", {"events": JSON.stringify($("#calendar").fullCalendar('clientEvents'))}, function(data) {
                        if(data.hasOwnProperty('success') && data.success === true) {
                                window.location = "../schedule/showCalendar.php";
                            } else {
                                alert("Error: Could not save Calendar. Sorry.");
                            }
                    });
                });
                
                //Sets the Create New Brew Event button
                $("#newBrew").click(function(e) {
                    e.preventDefault();
                    $("#createModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                    $("#createModal").modal("toggle");
                    $.get("createModal.php", function(data) {
                        $("#createModal .modal-body div").fadeOut(400, function() {
                            $("#createModal .modal-body").hide().html(data).slideDown("slow");
                            
                            //Datepicker Options (Lots!)
                            $(".datepicker").datetimepicker({
                                showTodayButton: true,
                                showClose: true,
                            });
                            $("#endDatepicker").data("DateTimePicker").disable();
                            $("#startDatepicker").on("dp.change", function(e) {
                                if($("#startDatepicker input").val() != "") {
                                    $("#endDatepicker").data("DateTimePicker").enable();
                                    $("#endDatepicker").data("DateTimePicker").minDate($("#startDatepicker").data("DateTimePicker").date());
                                } else {
                                    $("#endDatepicker input").val("");
                                    $("#endDatepicker").data("DateTimePicker").disable();
                                }
                            });
                            
                            $("#createModal").on("hidden.bs.modal", function(e) {
                                $("#createModal .modal-body").empty();
                            });
                        });
                    });
                });
                
                //Create Brew button
                $(".modalCreate").click(function() {
                    $.post("../api/schedule/create.php", $("#createBrewForm").serialize(), function(jsonData) {
                        if(jsonData.hasOwnProperty('success') && jsonData.success === true) {
                            window.location = "../schedule/showCalendar.php";
                        } else {
                            $("#errorMessage")
                            .html(jsonData.hasOwnProperty('error') ? jsonData.error : "Error: Could Not Contact Server")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                        }
                    });
                });
                
                // Set modal buttons
                $(".modalSave").click(function() {
                    $.post("../api/schedule/update.php", $("#updateBrewForm").serialize(), function(jsonData) {
                        if(jsonData.hasOwnProperty('success') && jsonData.success === true) {
                            window.location = "../schedule/showCalendar.php";
                        } else {
                            $("#errorMessage")
                            .html(jsonData.hasOwnProperty('error') ? jsonData.error : "Error: Could Not Contact Server")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                        }
                    });
                });
                
                $(".modalDelete").click(function() {
                    if(confirm("Are you sure you want to delete this brew? This is not reversable!")) {
                        $.post("../api/schedule/delete.php", {"brewId":$("#updateBrewForm > #brewId").val()} , function(jsonData) {
                            if(jsonData.hasOwnProperty('success') && jsonData.success === true) {
                                window.location = "../schedule/showCalendar.php";
                            } else {
                                $("#errorMessage")
                                .html(jsonData.hasOwnProperty('error') ? jsonData.error : "Error: Could Not Contact Server")
                                .slideDown("fast")
                                .delay(10000)
                                .slideUp(1000);
                            }
                        });
                    }
                });
                
                
            });
        </script>
    </head>
    <body>
        <?php require '../navbar.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-10">
                    <div id="calendar"></div>
                </div>
                <div class="col-sm-2">
                    <button type="button" id="calendarSave" class="btn btn-primary btn-block" data-toggle="tooltip" title="Disabled until feature created">Save Current <br>Calendar</button>
                    <br>
                    <button type="button" id="newBrew" class="btn btn-primary btn-block">Create new <br>Brew Event</button>
                </div>
            </div>
        </div>
        
        <!--Modal used for updating Events-->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel">Update Brew Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalDelete btn btn-danger">Delete Brew</button> 
                        <button type="button" class="modalSave btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!--Modal used for new Events-->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel">New Brew Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalCreate btn btn-primary">Create Brew</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>