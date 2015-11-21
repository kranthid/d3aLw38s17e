<?php
//CRUD for deals APIs
$app->get('/deals/all','getAllDealsData');
$app->get('/deals/dcat/:dealcategory/all','getAllDealsDataBasedOnCategory');
$app->get('/deals/dcat/:dealcategory/dsubcat/:dealsubcategory/all','getAllDealsDataBasedOnCategoryAndSubCategory');
$app->get('/deals/dealId/:dealId','getDealsDataBasedOnId');
$app->post('/deal/create','createNewDeal');
$app->put('/deals/dealId/:dealId','updateExistingDeal');
$app->delete('/deals/dealId/:dealId','deleteExistingDeal');
//------------------------------------------//
//Comments and likes for a deal
$app->get('/deals/uid/:uid/dcat/:category/dsubcat/:subcat/dealid/:id/comments','getAllDealcomments');
$app->get('/deals/uid/:uid/dcat/:category/dsubcat/:subcat/dealid/:id/likes','getAllDealLikes');
$app->post('/deals/uid/:uid/dcat/:category/dsubcat/:subcat/dealid/:id/comments/create','createNewCommentForDeal');
$app->put('/deals/uid/:uid/dcat/:category/dsubcat/:subcat/dealid/:id/comid/:cid/comments/update','updateCommentForDeal');
$app->delete('/uid/:uid/deals/dealid/:id/comments/cid/:cid/remove','deleteCommentForDeal');


/*
*
****CRUD for deals APIs functions start ****
*/

function getAllDealsData() {
    $sqlCommand = "SELECT * FROM deal WHERE 1";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function getAllDealsDataBasedOnCategory($dealcategory) {
    $queryParam = (string)$dealcategory;
    $sqlCommand = "SELECT * FROM deal WHERE deal_category = '$queryParam'";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        try {
            $findCommand = "SELECT * FROM deal_type WHERE id = '$queryParam'";
            $stmtFind = $db->query($findCommand);  
            $category = $stmtFind->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response = '{"deal":{"deal_type":"'.$category[0]->category_name.'","deals":'.json_encode($deals).'}}';
            echo $response;
        } catch(PDOException $e) {
            $dbConnection = null;
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }       
        //echo '{"deal":{"dealType":"$queryParam","deals":".json_encode($deals)"}'. '}';
    } catch(PDOException $e) {
        $db = null;
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}


function getAllDealsDataBasedOnCategoryAndSubCategory($mainCat,$subCat){
    $queryParamForMain = (string)$mainCat;
    $queryParamForSub = (string)$subCat;
    $sqlCommand = "SELECT * from deal where deal_category='$queryParamForMain' and deal_sub_category='$queryParamForSub'  and status=1 and end_date >= CURDATE()";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        try {
            $findMainCommand = "SELECT * FROM deal_type WHERE id = '$queryParamForMain'";
            $findSubCommand = "SELECT * FROM deal_sub_category WHERE id = '$queryParamForSub'";
            $stmtFindMain = $db->query($findMainCommand);
            $stmtFindSub  = $db-> query($findSubCommand);
            $categoryMain = $stmtFindMain->fetchAll(PDO::FETCH_OBJ);
            $categorySub = $stmtFindSub->fetchAll(PDO::FETCH_OBJ);
            //echo json_encode($categoryMain);
            //echo json_encode($categorySub);
            $db = null;
            $response = '{"deal":{"deal_type":"'.$categoryMain[0]->category_name.'","deal_topic":"'.$categorySub[0]->sub_category_name.'","deals":'.json_encode($deals).'}}';
            echo $response;
        } catch(PDOException $e) {
            $dbConnection = null;
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }
        //echo '{"dealCategories": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}


function getDealsDataBasedOnId($dealId) {
    $queryParam = (string)$dealId;
    $sqlCommand = "SELECT * FROM deal WHERE Id = '$queryParam'";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}


function createNewDeal(){
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    $sqlCommand = "INSERT INTO deal (user_id, deal_url, title, deal_price, deal_availablity,city_postcode
        ,deal_category,deal_sub_category,discount,discount_code,detail  
        ,prize,period ,deal_image ,deal_image_url
        ,tags,deal_rule,link_to_rule,apply_to,report,start_date,end_date,status) VALUES (:user_id, :deal_url, :title, :deal_price, :deal_availablity,:city_postcode
        ,:deal_category,:deal_sub_category,:discount,:discount_code,:detail  
        ,:prize,:period ,:deal_image ,:deal_image_url
        ,:tags,:deal_rule,:link_to_rule,:apply_to,:report,:start_date,:end_date,:status)";
    //echo ">>>>>>>>>>>>>>".$request->getBody();
    try {
        $db = getDB();
        $stmt = $db->prepare($sqlCommand);  
        $stmt->bindParam("user_id", $insert->user_id);
        $stmt->bindParam("deal_url", $insert->deal_url);
        $stmt->bindParam("title", $insert->title);
        $stmt->bindParam("deal_price", $insert->deal_price);
        $stmt->bindParam("deal_availablity", $insert->deal_availablity);
        $stmt->bindParam("city_postcode", $insert->city_postcode);
        $stmt->bindParam("deal_category", $insert->deal_category);
        $stmt->bindParam("deal_sub_category", $insert->deal_sub_category);
        $stmt->bindParam("discount", $insert->discount);
        $stmt->bindParam("discount_code", $insert->discount_code);
        $stmt->bindParam("detail", $insert->detail);
        $stmt->bindParam("prize", $insert->prize);
        $stmt->bindParam("period", $insert->period);
        $stmt->bindParam("deal_image", $insert->deal_image);
        $stmt->bindParam("deal_image_url", $insert->deal_image_url);         
        $stmt->bindParam("tags", $insert->tags);
        $stmt->bindParam("deal_rule", $insert->deal_rule);
        $stmt->bindParam("link_to_rule", $insert->link_to_rule);
        $stmt->bindParam("apply_to", $insert->apply_to);
        $stmt->bindParam("report", $insert->report);
        $stmt->bindParam("start_date", $insert->start_date);
        $stmt->bindParam("end_date", $insert->end_date);
        $stmt->bindParam("status", $insert->status);
        $status = $stmt->execute(); 
        $db = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function updateExistingDeal($dealId){
    $request = \Slim\Slim::getInstance()->request();
    $update = json_decode($request->getBody());
    $queryParam = (string)$dealId;
    $sqlCommand = "UPDATE deal SET Id = :Id, user_id = :user_id, deal_url = :deal_url, title = :title, deal_price = :deal_price, deal_availablity = :deal_availablity,city_postcode = :city_postcode
        ,deal_category = :deal_category,deal_topic = :deal_category,discount = :deal_category,discount_code = :discount_code,detail =:detail 
        ,prize = :prize,period =:period ,deal_image =:deal_image ,deal_image_url = :deal_image_url
        ,tags = :tags,deal_rule = :deal_rule,link_to_rule = :link_to_rule,apply_to = :apply_to,report = :report,deal_like =:deal_like  
        ,deal_dislike = :deal_dislike,start_date = :start_date,end_date = :end_date,status = :status WHERE Id = '$queryParam'";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sqlCommand);   
        $stmt->bindParam("Id", $update->Id);
        $stmt->bindParam("user_id", $update->user_id);
        $stmt->bindParam("deal_url", $update->deal_url);
        $stmt->bindParam("title", $update->title);
        $stmt->bindParam("deal_price", $update->deal_price);
        $stmt->bindParam("deal_availablity", $update->deal_availablity);
        $stmt->bindParam("city_postcode", $update->city_postcode);
        $stmt->bindParam("deal_category", $update->deal_category);
        $stmt->bindParam("deal_topic", $update->deal_topic);
        $stmt->bindParam("discount", $update->discount);
        $stmt->bindParam("discount_code", $update->discount_code);
        $stmt->bindParam("detail", $update->detail);
        $stmt->bindParam("prize", $update->prize);
        $stmt->bindParam("period", $update->period);
        $stmt->bindParam("deal_image", $update->deal_image);
        $stmt->bindParam("deal_image_url", $update->deal_image_url);         
        $stmt->bindParam("tags", $update->tags);
        $stmt->bindParam("deal_rule", $update->deal_rule);
        $stmt->bindParam("link_to_rule", $update->link_to_rule);
        $stmt->bindParam("apply_to", $update->apply_to);
        $stmt->bindParam("report", $update->report);
        $stmt->bindParam("deal_like", $update->deal_like);
        $stmt->bindParam("deal_dislike", $update->deal_dislike);
        $stmt->bindParam("start_date", $update->start_date);
        $stmt->bindParam("end_date", $update->end_date);
        $stmt->bindParam("status", $update->status);     
        $status = $stmt->execute(); 
        $db = null;
        echo '{"status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function deleteExistingDeal($dealId){
    $request = \Slim\Slim::getInstance()->request();
    $queryParam = (string)$dealId;
    $sqlCommand = "DELETE FROM deal WHERE Id = '$queryParam'";
    try {
        $db = getDB();
        $stmt = $db->prepare($sqlCommand);   
        //$stmt->bindParam("Id", $update->Id);   
        $status = $stmt->execute(); 
        $db = null;
        echo '{"Deletion status":'.$status.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}
/*
*
****CRUD for deals APIs functions end ****
*/
?>