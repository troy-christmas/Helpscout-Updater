<?php

if(isset($_POST['type']) && $_POST['type'] == 'Get' ){
    $token = getToken($_REQUEST['apikey']);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://docsapi.helpscout.net/v1/articles/'.$_REQUEST['article_id'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$token
    ),
    ));
    $response = curl_exec($curl);E:
    
    curl_close($curl);
    echo $response;die;
}

if(isset($_POST['endpoint']) && $_POST['endpoint'] == 'Update' ){
    $token = getToken($_REQUEST['apikey']);
    $curl = curl_init();
    $categories = $related_articles = $keywords = [];
    if(isset($_REQUEST['category']) && !empty($_REQUEST['category'])){
        $categories = explode(',',$_POST['category']);
    }
    if(isset($_REQUEST['related_articles']) && !empty($_REQUEST['related_articles'])){
        $related_articles = explode(',',$_POST['related_articles']);
    }
    if(isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords'])){
        $keywords = explode(',',$_POST['keywords']);
    }
    $postArray = [
        "collectionId" =>$_REQUEST['article_id'],
        "name"=> $_REQUEST['article_name'],
        "slug"=>  $_REQUEST['slug'],
        "text"=> $_REQUEST['article_text'],
        "reload" => $_REQUEST['realod'] == "Yes" ? true : false
    ];

    if(count($categories) > 0){
        $postArray['categories'] = $categories;
    }
    if(count($related_articles) > 0){
        $postArray['related'] = $related_articles;
    }
    if(count($keywords) > 0){
        $postArray['keywords'] = $keywords;
    }
    if(isset($_REQUEST['status'])){
        $postArray['status'] = $_REQUEST['status'];
    }
    $queryString = '';
    if($_REQUEST['realod'] == "Yes"){
        $queryString = '?reload=true';
    }

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://docsapi.helpscout.net/v1/articles/'.$_REQUEST['article_id'].$queryString,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS =>json_encode($postArray),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Basic '.$token
    ),
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if($httpcode == 200){
        echo json_encode(['message' => 'Success', 'response' => $response]); die;
    }else{
        $message = 'Internal server error.';
        if(!empty($response)){
            $res = json_decode($response, true);
            if(isset($res['error'])){
                $message = $res['error'];
            }else{
                if(count($res) > 0){
                    foreach($res as $result){
                        if(isset($result[0])){
                            $message = $result[0];
                        }
                    }
                }
            }
        }
        echo json_encode(['message' => 'Failed', 'response' => $message]); die;
    }
}

if(isset($_POST['endpoint']) && $_POST['endpoint'] == 'Create' ){
    $token = getToken($_REQUEST['apikey']);
    $curl = curl_init();
    $categories = $related_articles = $keywords = [];
    if(isset($_REQUEST['category']) && !empty($_REQUEST['category'])){
        $categories = explode(',',$_POST['category']);
    }
    if(isset($_REQUEST['related_articles']) && !empty($_REQUEST['related_articles'])){
        $related_articles = explode(',',$_POST['related_articles']);
    }
    if(isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords'])){
        $keywords = explode(',',$_POST['keywords']);
    }
    $postArray = [
        "collectionId" =>$_REQUEST['article_id'],
        "name"=> $_REQUEST['article_name'],
        "slug"=>  $_REQUEST['slug'],
        "text"=> $_REQUEST['article_text'],
        "reload" => $_REQUEST['realod'] == "Yes" ? true : false
    ];

    if(count($categories) > 0){
        $postArray['categories'] = $categories;
    }
    if(count($related_articles) > 0){
        $postArray['related'] = $related_articles;
    }
    if(count($keywords) > 0){
        $postArray['keywords'] = $keywords;
    }
    if(isset($_REQUEST['status'])){
        $postArray['status'] = $_REQUEST['status'];
    }
    $queryString = '';
    if($_REQUEST['realod'] == "Yes"){
        $queryString = '?reload=true';
    }
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://docsapi.helpscout.net/v1/articles'.$queryString,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($postArray),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Basic '.$token
    ),
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $error_msg = '';
    if($httpcode == 201 || $httpcode == 200){
        echo json_encode(['message' => 'Success', 'response' => $response]); die;
    }else{
        $message = 'Internal server error.';
        if(!empty($response)){
            $res = json_decode($response, true);
            if(isset($res['error'])){
                $message = $res['error'];
            }else{
                if(count($res) > 0){
                    foreach($res as $result){
                        if(isset($result[0])){
                            $message = $result[0];
                        }
                    }
                }
            }
        }
        echo json_encode(['message' => 'Failed', 'response' => $message]); die;
    }
}

function getToken($apikey){
    return base64_encode($apikey.':123456');
}
?>