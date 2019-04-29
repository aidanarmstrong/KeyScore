$(document).ready(function() {

    // get all current projects
    $.ajax({
        type: "GET",
        url: '../assets/api/get-projects.php',
        dataType: "json",
        success: function(json){
            
            createJSTrees(json)
        }
    });

    //  jsTree view
    function createJSTrees(jsonData) {
        $('#projects_head').jstree({
            "plugins" : ["contextmenu", "dnd", "search", "state", "types", "wholerow"],
            'contextmenu' : {
                'items' : customMenu,
            },
            'core': {
                "check_callback": true,
                'data': jsonData
            },
            'types': {
                "#" : {
                    "max_children" : 1,
                    "max_depth" : 4,
                    "valid_children" : ["root"]
                },
                "child": {
                    "icon": "fa fa-file"
                },
                "root": {
                    "icon": "fa fa-folder-open",
                    "valid_children" : ["default"]
                },
                "default": {
                    "icon": "fa fa-folder",
                    "valid_children" : ["default","file"]
                },
                "file" : {
                    "icon" : "fa fa-file",
                    "valid_children" : []
                }
            },
            'check_callback' : true,
            'themes' : {
              'responsive' : false
            }
        }).on('create_node.jstree', function (e, data) {   
            var parentId = data.parent;
            var nodeType = data.node.type;

            // console.log(data.node.text);

            // ajax to save created node to server  
            $.ajax({
                type: "post",
                url: '../assets/api/add-node.php',
                data: {
                    parentId: parentId,
                    nodeType: nodeType,
                },
                success: function(data){
                    console.log(data);
                    // if(data === "node/success"){
                    //     window.location.reload();
                    // }else if (data === "error/unknown"){
                    //     alert("Unknown error occured!"); // clear added node if its not stored
                    //     setTimeout( () =>{
                    //         window.location.reload();
                    //     }, 2000);
                    // }
                }
            });

          }).on('rename_node.jstree', function (e, data) {

                var nodeName = data.text;
                var nodeId = data.node.id;
                var nodeParent = data.node.parent;

                console.log(nodeParent);

               // ajax to update node name to server
                $.ajax({
                    type: "post",
                    url: '../assets/api/rename-node.php',
                    data: {
                        nodeName: nodeName,
                        nodeId: nodeId,
                        nodeParent: nodeParent,
                    },
                    success: function(data){
                        console.log(data);
                        // if(data === "success/re-named"){
                        //     window.location.reload();
                        // }
                        // else if(data === "error/failed-rename"){
                        //     if(confirm('unable to rename!')){
                        //         window.location.reload();
                        //     }
                        // }
                    }
                });
          }).on('delete_node.jstree', function (e, data) {

            var nodeId = data.node.id;

            // ajax to delete node from database
            $.ajax({
                type: "post",
                url: '../assets/api/delete-node.php',
                data: {
                    nodeId: nodeId,
                },
                success: function(data){
                    console.log(data);
                    if(data == "error/nodes-not-deleted"){
                        if(confirm('unable to delete!')){
                            window.location.reload();
                        }
                    }
                }
            });

          });
        
    }

    // custom jsTree context menu
    function customMenu(node) {
        // The default set of all items
        var control;

        var tree = $("#projects_head").jstree(true); // tree name
        var items = {
            newFolderItem: {
                label: "New Folder",
                icon: "fa fa-folder",
                action: function (obj) { 
                    //create folder 
                    $node = tree.create_node(node, {text: 'New Folder', type: 'default'});
                    tree.edit($node);
                    
                }
            },
            newFileItem: {
                separator_before: 'false',
                separator_after: 'false',
                label: "New File",
                icon: "fa fa-file",
                // submenu: {
                //     "HTML5": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "HTML5",
                //         "icon": "fa fa-code",
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-code' });
                //             tree.edit($node);
                //         }
                //     },
                //     "CSS": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "CSS",
                //         "icon": "fa fa-code",
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-code' });
                //             tree.edit($node);

                //         }
                //     },
                //     "Java": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "Java",
                //         "icon": "fa fa-code",
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-code' });
                //             tree.edit($node);
                //         }
                //     },
                //     "JavaScript": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "JavaScript",
                //         "icon": "fa fa-code",
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-code' });
                //             tree.edit($node);
                //         }
                //     },
                //     "PHP": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "PHP",
                //         "icon": 'fa fa-code',
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-code' });
                //             tree.edit($node);
                //         }
                //     },
                //     "Text": {
                //         "seperator_before": false,
                //         "seperator_after": false,
                //         "label": "File",
                //         "icon": 'fa fa-file',
                //         action: function (obj) {
                //             $node = tree.create_node(node, { text: 'Untitled file', type: 'file', icon: 'fa fa-file' });
                //             tree.edit($node);
                //         }
                //     },
                    
                // }
                    action: function (obj) {
                        // create file 
                        $node = tree.create_node(node, {text: 'New File', type: 'file', icon: 'fa fa-file'});
                        tree.edit($node);
                        
                    }
            },
            renameItem: {
                "separator_before": false,
                "separator_after": false,
                "label": "Rename",
                "icon": "fa fa-edit",
                "action": function (obj) { 
                    // rename file
                    $node = tree.edit(node);
                }
            },
            deleteItem: {
                label: "Delete",
                icon: "fa fa-trash",
                action: function (obj) { 
                    // delete file
                    if(confirm('Are you sure to remove this? it will be deleted permanently')){
                        tree.delete_node(node);
                    }
                },
            },
            copyItem: {
                label: "Copy",
                icon: "fa fa-copy",
                action: function (node) { $(node).addClass("copy"); return { copyItem: this.copy(node) }; }
            },
            cutItem: {
                label: "Cut",
                icon: "fa fa-cut",
                action: function (node) { $(node).addClass("cut"); return { cutItem: this.cut(node) }; }
            },
            pasteItem: {
                label: "Paste",
                icon: "fa fa-paste",
                action: function (node) { $(node).addClass("paste"); return { pasteItem: this.paste(node) }; }
            }
        };

        if (node.type === 'level_1') {
            delete items.item2;
        } else if (node.type === 'level_2') {
            delete items.item1;
        }

        return items;
    }


    // change folder icon to open on click
    $('#projects_head').on('open_node.jstree', function (e, data) {
        data.instance.set_icon(data.node, "fa fa-folder-open");
    }).on('close_node.jstree', function (e, data) {
         data.instance.set_icon(data.node, "fa fa-folder"); 
    });

    // get project id
    $('#projects_head').on("select_node.jstree", function (e, data) { 
        var project_id = data.node.id;
        
        console.log(project_id);
        // alert(project_id);
    });

    // adds project to database 
    $('#addProject').click(function(){
        var projectName = $('#name').val();

        if(projectName === ''){
            $("#error-name").html('Please enter a project name *');
        }else if(projectName != ''){
            $("#error-name").html('');

            $.ajax({
                type: "post",
                url: '../assets/api/add-project.php',
                data: {
                    projectName: projectName,
                },
                success: function(data){
                    console.log(data);
                    if(data === "error/no-project-name"){
                        $("#error-name").html("Please enter a project name *");
                    }else if(data === "success/project-added"){
                        $("#projects-head").append("<li class='projects-file'><span class='folder'><i class='fa fa-folder'><label class='filename'>"+projectName+"</label></i></span></li>");
                        $("#addProjectModal").modal("toggle");
                        $("#addProjectsForm")[0].reset();
                        window.location.reload();
                    }else if(data === "error/project-name-used"){
                        $("#error-name").html("Project already created *");
                    }else{
                        $("#error").html("<label class='alert alert-danger'>Unkown error creating project</label>");
                    }
                }
            });

        }
    });


    $('#name').bind("enterKey",function(e){
        $("#addProjectModal").show();
    });
    $('#name').keyup(function(e){
        if(e.keyCode == 13){
            $(this).trigger("enterKey");
            $("#addProjectModal").show();
        }
    });

    // Logout of account
    $(".logout").click(function(){
        $("#logoutDiv").show();

        setTimeout( () => {
            window.location = "../assets/api/logout.php";
        }, 1000);
    });


    // Page refresh
    $("#refeshPage").click(function(){
        window.location.reload();
    });
    // auto save message
    $("#autoSave").click(function(){
        $("#alertMessage").show();
        $("#alertMessage").html("<p class='alert alert-success'>Auto save enabled <i class='fa fa-check'></i></p>");
        $("#alertMessage").fadeOut(2000);
    });

    // saving files

     $("#saving").click(function(){
        $("#alertMessage").show();
        $("#alertMessage").html("<p class='alert alert-success'>Files saved <i class='fa fa-check'></i></p>");
        $("#alertMessage").fadeOut(2000);
    });

    
    function getModeByFileExtension(path){
        var modelist = ace.require("ace/ext/modelist");
        return modelist.getModeForPath(path).mode;
    }
    
    
    var filename = "../assets/files/Hllo/jo.txt";
    // In this case "ace/mode/javascript"
    var mode = getModeByFileExtension(filename);
    editor.getSession().setMode(mode);

});