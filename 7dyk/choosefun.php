<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/6
 * Time: 14:41
 */
function chooseFile($gender,$education,$height,$age){
    $result[0]=gender($gender);
    $result[1]=education($education);
    $result[2]=height($height);
    $result[3]=age($age);
    return $result;
}


function saveFile($gender,$education,$height,$age){
    $result[0]=genderSave($gender);
    $result[1]=education($education);
    $result[2]=height($height);
    $result[3]=age($age);
    return $result;
}


//choose gender
function gender($gender)
{
    switch ($gender){
        case "男":
            return 1;
        case "女":
            return 2;
    }
}


//save gender
function genderSave($gender)
{
    switch ($gender){
        case "男":
            return 2;
        case "女":
            return 1;
    }
}

function education($education)
{
    switch ($education){
        case "本科":
            return 1;
        case "硕士":
            return 2;
        case "博士":
            return 3;
        case "大专":
            return 4;
        case "大专以下":
            return 5;
    }
}


function height($height){
    switch ($height){
        case $height<=160:
            return 1;
        case $height<=170:
            return 2;
        case $height<=180:
            return 3;
        default:
            return 4;
    }
}

function age($age){
    switch ($age){
        case $age<=20:
            return 1;
        case $age<=25:
            return 2;
        case $age<=30:
            return 3;
        default:
            return 4;
    }
}