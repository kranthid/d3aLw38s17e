<?php
//CRUD for deals APIs
$app->get('/deal/all','getAllDeals');
$app->get('/deal/category/:catid/all','getAllDealsByCategory');
$app->get('/deal/category/:catid/subcategory/:subcatid/all','getAllDealsByCategoryAndSubCategory');
$app->get('/deal/:dealid','getDealsById');
$app->get('/deal/latestdeals','getLatestDeals');

$app->post('/deal/create','createNewDeal');
$app->put('/deal/:dealid/update','updateExistingDeal');
$app->delete('/deal/:dealid','deleteExistingDeal');
//------------------------------------------//

//Comments and likes for a deal
$app->get('/deal/:dealid/comments','getAllDealcomments');
$app->post('/deal/:dealid/userid/:uid/comments/create','createNewCommentForDeal');

$app->post('/deal/:dealid/userid/:uid/socialactivity/:actvty','postLikesAndDislikesForDeals');
$app->post('/deal/comments/:cmtid/userid/:uid/socialactivity/:actvty','postLikesAndDislikesForComments');


/*
*
****CRUD for deals APIs functions start ****
*/

function getAllDeals() {
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

function getAllDealsByCategory($dealcategory) {
    $queryParam = (string)$dealcategory;
    $sqlCommand = "SELECT * FROM deal WHERE deal_category = '$queryParam'";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($deals as $key => $value) {
            $tmpId = $value->id;
            $sqlCommandForSocialLikes ="SELECT count(*) as deal_like FROM deal_social_activity WHERE
                                        deal_social_activity.deal_id = '$tmpId' AND deal_social_activity.activity_type = 
                                        'LIKE' GROUP BY deal_social_activity.activity_type";
            $sqlCommandForSocialDisLikes ="SELECT count(*) as deal_dis_like FROM deal_social_activity WHERE
                                        deal_social_activity.deal_id = '$tmpId' AND deal_social_activity.activity_type = 
                                        'DISLIKE' GROUP BY deal_social_activity.activity_type";
            $stmtLikeFinder = $db->query($sqlCommandForSocialLikes);  
            $likes = $stmtLikeFinder->fetchAll(PDO::FETCH_OBJ);
            $stmtDisLikeFinder = $db->query($sqlCommandForSocialDisLikes);  
            $dislikes = $stmtDisLikeFinder->fetchAll(PDO::FETCH_OBJ);
            if(count($likes)){
                $deals[$key]->deal_like=$likes[0]->deal_like;   
            }else{
                $deals[$key]->deal_like=0;
            }
            if(count($dislikes)){
                $deals[$key]->deal_dislike=$dislikes[0]->deal_dis_like; 
            }else{
                $deals[$key]->deal_dislike=0;
            }

        }
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


function getAllDealsByCategoryAndSubCategory($mainCat,$subCat){
    $queryParamForMain = (string)$mainCat;
    $queryParamForSub = (string)$subCat;
    $sqlCommand = "SELECT * from deal where deal_category='$queryParamForMain' and deal_sub_category='$queryParamForSub'  and status=1 and end_date >= CURDATE()";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);  
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($deals as $key => $value) {
            $tmpId = $value->id;
            $sqlCommandForSocialLikes ="SELECT count(*) as deal_like FROM deal_social_activity WHERE
                                        deal_social_activity.deal_id = '$tmpId' AND deal_social_activity.activity_type = 
                                        'LIKE' GROUP BY deal_social_activity.activity_type";
            $sqlCommandForSocialDisLikes ="SELECT count(*) as deal_dis_like FROM deal_social_activity WHERE
                                        deal_social_activity.deal_id = '$tmpId' AND deal_social_activity.activity_type = 
                                        'DISLIKE' GROUP BY deal_social_activity.activity_type";
            $stmtLikeFinder = $db->query($sqlCommandForSocialLikes);  
            $likes = $stmtLikeFinder->fetchAll(PDO::FETCH_OBJ);
            $stmtDisLikeFinder = $db->query($sqlCommandForSocialDisLikes);  
            $dislikes = $stmtDisLikeFinder->fetchAll(PDO::FETCH_OBJ);
            if(count($likes)){
                $deals[$key]->deal_like=$likes[0]->deal_like;   
            }else{
                $deals[$key]->deal_like=0;
            }
            if(count($dislikes)){
                $deals[$key]->deal_dislike=$dislikes[0]->deal_dis_like; 
            }else{
                $deals[$key]->deal_dislike=0;
            }

        }
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


function getDealsById($dealId) {
    $queryParamForDealId = (string)$dealId;
    $sqlCommand = "SELECT * FROM deal WHERE Id = '$queryParamForDealId'";
    $sqlCommandForSocialLikes =  "SELECT count(*) as deal_like FROM deal_social_activity WHERE
                            deal_social_activity.deal_id = '$queryParamForDealId' AND deal_social_activity.activity_type = 'LIKE' GROUP BY deal_social_activity.activity_type";
    $sqlCommandForSocialDisLikes =  "SELECT count(*) as deal_dis_like FROM deal_social_activity WHERE
                                    deal_social_activity.deal_id = '$queryParamForDealId' AND deal_social_activity.activity_type = 'DISLIKE' GROUP BY deal_social_activity.activity_type";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);
        $likes =  $db->query($sqlCommandForSocialLikes);
        $dislikes =  $db->query($sqlCommandForSocialDisLikes); 
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $likesData = $likes->fetchAll(PDO::FETCH_OBJ);
        $dislikesData = $dislikes->fetchAll(PDO::FETCH_OBJ);
        $categoryParam = $deals[0]->deal_category;
        $subCategoryParam = $deals[0]->deal_sub_category;
        $findMainCategory = "SELECT * FROM deal_type WHERE id = '$categoryParam'";
        $findSubCategory= "SELECT * FROM deal_sub_category WHERE id = '$subCategoryParam'";
        $stmtFindMain = $db->query($findMainCategory);
        $stmtFindSub  = $db-> query($findSubCategory);
        $categoryMain = $stmtFindMain->fetchAll(PDO::FETCH_OBJ);
        $categorySub = $stmtFindSub->fetchAll(PDO::FETCH_OBJ);
        $db = null;
            if(count($likesData)){
                $likesData = $likesData[0]->deal_like;
            }else{
                $likesData = 0;
            }
            if(count($dislikesData)){
                $dislikesData = $dislikesData[0]->deal_dis_like;
            }else{
                $dislikesData = 0;
            }
            $deal_data = array(
              'deals' => array(
                'deal_type' => $categoryMain[0] ->category_name,
                'deal_topic' => $categorySub[0]->sub_category_name,
                 'deal_id' => $deals[0] ->id,
                  'deal_url'=> $deals[0] ->deal_url,
                  'title'=>$deals[0] ->title,
                  'detail'=>$deals[0] ->detail,
                  'deal_image'=>$deals[0] ->deal_image,
                  'deal_image_url'=>$deals[0] ->deal_image_url,
                  'tags'=>$deals[0] ->tags,
                  'report'=>$deals[0] ->report,
                  'deal_like'=> $likesData,
                  'deal_dislike'=> $dislikesData, 
              )
            );
            echo json_encode($deal_data);
        //echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function getLatestDeals() {
    
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


function getAllDealcomments($id){
    $queryParamForDealId = (string)$id;
    $sqlCommand = "SELECT * FROM deal WHERE Id = '$queryParamForDealId'";
    $sqlCommandForSocialLikes =  "SELECT count(*) as deal_like FROM deal_social_activity WHERE
                            deal_social_activity.deal_id = '$queryParamForDealId' AND deal_social_activity.activity_type = 'LIKE' GROUP BY deal_social_activity.activity_type";
    $sqlCommandForSocialDisLikes =  "SELECT count(*) as deal_dis_like FROM deal_social_activity WHERE
                                    deal_social_activity.deal_id = '$queryParamForDealId' AND deal_social_activity.activity_type = 'DISLIKE' GROUP BY deal_social_activity.activity_type";
    $sqlCommandForComments = "SELECT * FROM comments WHERE deal_id = '$queryParamForDealId' ";
    try {
        $db = getDB();
        $stmt = $db->query($sqlCommand);
        $likes =  $db->query($sqlCommandForSocialLikes);
        $dislikes =  $db->query($sqlCommandForSocialDisLikes); 
        $comments = $db->query($sqlCommandForComments);
        $deals = $stmt->fetchAll(PDO::FETCH_OBJ);
        $likesData = $likes->fetchAll(PDO::FETCH_OBJ);
        $dislikesData = $dislikes->fetchAll(PDO::FETCH_OBJ);
        $commentsData = $comments->fetchAll(PDO::FETCH_OBJ);
        $categoryParam = $deals[0]->deal_category;
        $subCategoryParam = $deals[0]->deal_sub_category;
        $findMainCategory = "SELECT * FROM deal_type WHERE id = '$categoryParam'";
        $findSubCategory= "SELECT * FROM deal_sub_category WHERE id = '$subCategoryParam'";
        $stmtFindMain = $db->query($findMainCategory);
        $stmtFindSub  = $db-> query($findSubCategory);
        $categoryMain = $stmtFindMain->fetchAll(PDO::FETCH_OBJ);
        $categorySub = $stmtFindSub->fetchAll(PDO::FETCH_OBJ);
        $db = null;
            if(count($likesData)){
                $likesData = $likesData[0]->deal_like;
            }else{
                $likesData = 0;
            }
            if(count($dislikesData)){
                $dislikesData = $dislikesData[0]->deal_dis_like;
            }else{
                $dislikesData = 0;
            }
            $deal_data = array(
                'deals' => array(
                    'deal_type' => $categoryMain[0] ->category_name,
                    'deal_topic' => $categorySub[0]->sub_category_name,
                    'deal_id' => $deals[0] ->id,
                    'deal_url'=> $deals[0] ->deal_url,
                    'title'=>$deals[0] ->title,
                    'detail'=>$deals[0] ->detail,
                    'deal_image'=>$deals[0] ->deal_image,
                    'deal_image_url'=>$deals[0] ->deal_image_url,
                    'tags'=>$deals[0] ->tags,
                    'report'=>$deals[0] ->report,
                    'deal_like'=> $likesData,
                    'deal_dislike'=> $dislikesData,
                    'deal_comments'=> $commentsData
                )
            );
            echo json_encode($deal_data);
        //echo '{"deal": ' . json_encode($deals) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}
function createNewCommentForDeal($dealid, $uid){
    $queryParamForDealId = (string)$dealid;
    $queryParamForUserId = (string)$uid;
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());

    //ON DUPLICATE KEY UPDATE comments SET deal_id=:deal_id,user_id=:user_id,comment_content=:comment_content,comment_date=:comment_date,comment_parent=:comment_parent,comment_report=:comment_report,comment_status=:comment_status,category_id=:category_id, sub_category_id=:sub_category_id

    $sqlCommandForComments = "INSERT INTO comments 
    (deal_id,user_id,user_name,comment_content,comment_date,comment_parent,comment_report,comment_status) VALUES (:deal_id,:user_id,:user_name,:comment_content,:comment_date,:comment_parent,:comment_report,:comment_status)";
    try {
            $db = getDB();
            $stmt = $db->prepare($sqlCommandForComments);  
            $stmt->bindParam("deal_id", $dealid);
            $stmt->bindParam("user_id", $uid);
            $stmt->bindParam("user_name", $insert->user_name);
            $stmt->bindParam("comment_content", $insert->comment_content);
            $stmt->bindParam("comment_date", $insert->comment_date);
            $stmt->bindParam("comment_parent", $insert->comment_parent);
            $stmt->bindParam("comment_report", $insert->comment_report);
            $stmt->bindParam("comment_status", $insert->comment_status);
            $status = $stmt->execute(); 
            $db = null;
            echo '{"status":'.$status.'}';
        } catch(PDOException $e) {
            $dbConnection = null;
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }
}
function postLikesAndDislikesForDeals($dealid,$uid,$action){
    $queryParamForDealId = (string)$dealid;
    $queryParamForUserId = (string)$uid;
    $queryParamForSocialAction = strtoupper((string)$action);
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    //echo $request->getBody();
    $sqlCommandForSocial = "INSERT INTO deal_social_activity 
    (deal_id,user_id,activity_type,activity_date) VALUES 
    (:deal_id,:user_id,:activity_type,NOW()) ON DUPLICATE KEY UPDATE deal_id=:deal_id,user_id=:user_id,activity_type=:activity_type,activity_date=NOW()";
    
    try {
            $db = getDB();
            
            $stmt = $db->prepare($sqlCommandForSocial);  
            $stmt->bindParam("deal_id", $queryParamForDealId);
            $stmt->bindParam("user_id", $queryParamForUserId);
            $stmt->bindParam("activity_type", $queryParamForSocialAction);
            $status = $stmt->execute(); 
            $db = null;
            echo '{"status":'.$status.'}';
        } catch(PDOException $e) {
            $dbConnection = null;
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }

}
function postLikesAndDislikesForComments($commentid,$uid,$action){
    $queryParamForCommentId = (string)$commentid;
    $queryParamForUserId = (string)$uid;
    $queryParamForSocialAction = strtoupper((string)$action);
    $request = \Slim\Slim::getInstance()->request();
    $insert = json_decode($request->getBody());
    //echo $request->getBody();
    $sqlCommandForSocial = "INSERT INTO deal_social_activity 
    (comment_id,user_id,activity_type,activity_date) VALUES 
    (:comment_id,:user_id,:activity_type,NOW()) ON DUPLICATE KEY UPDATE comment_id=:comment_id,user_id=:user_id,activity_type=:activity_type,activity_date=NOW()";
    
    try {
            $db = getDB();
            
            $stmt = $db->prepare($sqlCommandForSocial);  
            $stmt->bindParam("comment_id", $queryParamForCommentId);
            $stmt->bindParam("user_id", $queryParamForUserId);
            $stmt->bindParam("activity_type", $queryParamForSocialAction);
            $status = $stmt->execute(); 
            $db = null;
            echo '{"status":'.$status.'}';
        } catch(PDOException $e) {
            $dbConnection = null;
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }

}

/*
*
****End of CRUD for deals APIs functions ****
*/
?>