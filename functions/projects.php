<?php

    require_once __DIR__ . "/tags.php";
    require_once __DIR__ . "/../handler/db.php";

function addProject(string $name, string $description, string $hardware, string $missing, string $problems, int $eventId, array $tags){
        global $__DB;
        $sql = "INSERT into projects 
(name, description, hardware, missing,problem,eventId,userId) 
values 
(" . $__DB->real_escape_string($name) . ", " .
            $__DB->real_escape_string($description) . ", " .
            $__DB->real_escape_string($hardware) . ", " .
            $__DB->real_escape_string($missing) . ", " .
            $__DB->real_escape_string($problems) . ", " .
            $__DB->real_escape_string($eventId) . ", 1)";

        $__DB->query($sql);
        $sql_getId = "SELECT id FROM projects where name='" . $__DB->real_escape_string($name) . "' and description=" . $__DB->real_escape_string($description) . ") order by insert_time desc limit 1";
        $projectid = $__DB->query($sql_getId)->fetch_assoc()['id'];

        $sql_tagproject = "INSERT INTO tagged values ";
        foreach ($tags as $key => $color){
            if($tag = checkTag($key, $color)){
                $sql_tagproject .= "(" . $projectid . ", " . $tag['id'] . "),";
            }else{
                $sql_addtag = "INSERT INTO tags (value, color) VALUES ('$key', '$color')";
                $__DB->query($sql_addtag);
                $sql_gettag = "SELECT id from tags where value='$key' and color='$color' limit 1";
                $tagId = $__DB->query($sql_gettag)->fetch_assoc()['id'];
                $sql_tagproject .= "(" . $projectid . ", " . $tag['id'] . "),";
            }
        }
        $sql_tagproject = rtrim($sql_tagproject, ",") . ";";
}