<?php
/**
 * Created by PhpStorm.
 * User: aidanarmstrong
 * Date: 28/01/2019
 * Time: 14:04
 */

 session_start();
include "../assets/api/check.php";
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- <link rel="icon" type="image/png" href="./assets/img//favicon.png"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!-- jsTree -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <title>KeyScore</title>


    <!-- CSS Files -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />

    <!--  Font Awesome  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    <div class="conatiner-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    File
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addProjectModal">New Project</a>
                        <a class="dropdown-item" href="#">Save</a>
                        <a class="dropdown-item" href="#">Save All</a>
                        <a id="autoSave" class="dropdown-item" href="#">Auto Save</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Edit
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Undo</a>
                        <a class="dropdown-item" href="#">Redo</a>
                        <a class="dropdown-item" href="#">Cut</a>
                        <a class="dropdown-item" href="#">Copy</a>
                        <a class="dropdown-item" href="#">Paste</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">View</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Run
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Run Debuger</a>
                        <a class="dropdown-item" href="#">Run in Window</a>
                        <a class="dropdown-item" href="#">Run in Terminal</a>
                        <a class="dropdown-item" href="#">Run in Iphone</a>
                        <a class="dropdown-item" href="#">Run in Android</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tools
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Emulator</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    VCS
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Git</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Terminal
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">New Terminal</a>
                        <a class="dropdown-item" href="#">Split Terminal</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['name'];?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#aboutModal" data-toggle="modal" data-target="#profileModal"><i class="fa fa-address-card"></i> Account</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-cog"></i>  Settings</a>
                        <a class="dropdown-item logout" href="#"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                </li>
        </ul>
        </div>
    </nav>
    
    </div>
    <br>
    <div class="container body">
        <div class="row">
            <div id="projects" class="col-md-2">
                <h6 class="header">
                    Projects
                    <span class="float-right">
                        <a id="refeshPage" class="btn text-white" style="margin-right: 10px;">
                                <i class="fa fa-refresh"></i>
                        </a>
                        <a class="btn" data-toggle="modal" data-target="#addProjectModal">
                            <i class="fa fa-plus"></i>
                        </a>
                    </span>
                </h6>
               <div id="projects_head"></div> <!-- Tree div-->

            </div>
            <div class="col-md-10">
                <div class="code">
                        <div class="editor-container">
                            <div id="editor"></div> <!-- editor-->
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alertMessage"></div>

    <!--  add project modal  -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModal" aria-hidden="true" style="color: #222222">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectLabel">Add Project</h5>
                </div>
                <div class="modal-body">
                    <div class="conatiner">
                        <form id="addProjectsForm">
                        <div class="card" style="background-color: transparent!important; border-color: transparent!important;">
                        <div class="card-body">
                            <!--Body-->
                            <div class="form-group">
                                <input type="text" id="name" class="form-control" autocomplete="off" required>
                                <label class="form-control-placeholder" for="name">Project Name</label>
                                <label class="text-danger"><small id='error-name'></small></label>
                                <div id="error" class="text-center"></div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
                                <a class="btn btn-primary" id="addProject">Add</a>
                            </div>
                        </div>
                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true" style="color: #222222">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 25%;" >
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="conatiner" style="margin-bottom: 6%;">
                        <form id="profileForm">
                            <div class="container text-inline">  
                                <img src="https://cdn4.iconfinder.com/data/icons/web-ui-color/128/Account-512.png" alt="Profile picture" class="img-fluid pull-left" width="80" height="80"/>
                                <br>
                                <div class="container-fluid" style="margin-left: 18%;">
                                    <h6>
                                        Name: <?php echo $_SESSION['name'];?>
                                        <small><a href="#">Upgrade account</a></small>
                                    </h6>
                                    <span>Most used Languages:</span><br>
                                    <span class="badge badge-secondary">PHP</span>
                                    <span class="badge badge-light">HTML5</span>
                                    <span class="badge badge-success">CSS</span>
                                    <span class="badge badge-warning">JavaScript</span>
                                </div>
                                <hr style="background-color: rgba(255,255,255,.3);"/>
                                <div class="conatiner">
                                    <a href="https://www.github.com/login" target="_blank" class="btn btn-primary"><i class="fa fa-github"></i> Github</a>
                                    <a href="https://www.bitbucket.com/login" target="_blank" class="btn btn-light text-dark"><i class="fa fa-bitbucket"></i> Bitbucket</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="logoutDiv">
        <div class="container">
            <div class="text-center text-dark">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                Logging out...
                <br>
                <div class="spinner-grow text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>


 <!-- All Script Files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

<!--    <script type="text/javascript" src="assets/js/app.js"></script>-->
    <script src="../assets/ace-builds-master/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js" type="text/javascript"></script>


    <!--  js treeview -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.7/themes/default/style.min.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.7/jstree.min.js"></script>
    <script src="../assets/ace-builds-master/src/ext-modelist.js"></script>
    
    <!-- Ace code editor -->
    <script>
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/ambiance");
        editor.session.setMode("ace/mode/html");
    </script>

    <script type="text/javascript" src="../assets/js/main.js"></script>

</body>
</html>




