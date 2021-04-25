<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $toshowProb = '';

    if(isset($_COOKIE['registeredPRODUCT'])) {
				$toshowProb =  $_COOKIE['registeredPRODUCT'];
					unset($_COOKIE['registeredPRODUCT']);
					// empty value and expiration one hour before
					setcookie('registeredPRODUCT', '', time() - 3600, "/");
				} 
    
?>

<!DOCTYPE html>
<html>
<head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <title>Home Page</title>


        <script>
			function toggle(elemToHide) {
                var elementFinalToHide = document.getElementById(elemToHide);
                var fff = "#" +elemToHide;
                console.log(fff);
				if (elementFinalToHide.style.display == "block") {
                    $( fff ).hide(1000);
                    elementFinalToHide.style.display = "none";
				}
				else {
                    $( fff ).show(1000);
                    elementFinalToHide.style.display = "block";
				}
			}
		</script>

        <script>
        
            function updateAllCheckBoxValues(productId, likeVar, FavorVar, bookVar, userIDVar){
                var productTosend = document.getElementById(productId);
                var stringToSendPost = 'product=' + productTosend.id + '&user=' + userIDVar;

                if (likeVar.checked){
                    stringToSendPost += '&like=true'
                }else{
                    stringToSendPost += '&like=false'
                }
                if (FavorVar.checked){
                    stringToSendPost += '&favorite=true'
                }else{
                    stringToSendPost += '&favorite=false'
                }
                if (bookVar.checked){
                    stringToSendPost += '&bookmark=true'
                }else{
                    stringToSendPost += '&bookmark=false'
                }

                var whatDiv = '#saveDiv' + productTosend.id;
                console.log(whatDiv);
                    $.ajax({
                            type: "POST",
                            url: "saveTagsFavoritesBookmarks.php",
                            data: stringToSendPost,
                            cache: false,
                            success: function(html) {
                                $(whatDiv).html(html);
                        }
                    });
                return false;
            }
            
        </script>
</head>
<body>


<div>
<?php include 'menucontext.php'; ?>

    <div style=" text-align: right; color:white;">
       signed in as:!!!
    </div>
</div>
<div style="text-align:center;">
<div style="border:1px solid #000; display:inline-block; text-align:center;">
    <h1>What are you looking for?</h1>
    
    <form action="<?php $_PHP_SELF ?>" method ="POST">
        <input type ="text" name="StringToSearch" required></input>
        <input type="submit" name="searcforPRODUCTS" value="Search">
    </form>

            <?php

               $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $stopwords = array("a", "about", "above", "across", "after", "afterwards", "again", "against", "all", "almost",
            "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", "amoungst",
            "amount", "an", "and", "another", "any", "anyhow", "anyone", "anything", "anyway", "anywhere",
            "are", "aren't", "around", "as", "at", "back", "be", "became", "because", "become",
            "becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides",
            "between", "beyond", "bill", "both", "bottom", "but", "by", "call", "can", "cannot",
            "cant", "can't", "co", "computer", "con", "could", "couldnt", "couldn't", "cry", "de",
            "describe", "detail", "didn't", "do", "doesn't", "done", "don't", "down", "due", "during",
            "each", "eg", "eight", "either", "eleven", "else", "elsewhere", "empty", "enough", "etc",
            "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify",
            "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found",
            "four", "from", "front", "full", "further", "get", "give", "go", "had", "hadn't",
            "has", "hasnt", "hasn't", "have", "haven't", "he", "he'd", "he'll", "hence", "her",
            "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "him",
            "himself", "his", "how", "however", "how's", "hundred", "i", "i'd", "ie", "if",
            "i'll", "i'm", "in", "inc", "indeed", "interest", "into", "is", "isn't", "it",
            "its", "it's", "itself", "i've", "keep", "last", "latter", "latterly", "least", "less",
            "let's", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine",
            "more", "moreover", "most", "mostly", "move", "much", "must", "mustn't", "my", "myself",
            "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none",
            "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on",
            "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours",
            "ourselves", "out", "over", "own", "part", "per", "perhaps", "please", "put", "rather",
            "re", "rt", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several",
            "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "show", "side", "since",
            "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes",
            "somewhere", "still", "such", "system", "take", "ten", "than", "that", "that's", "the",
            "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein",
            "there's", "thereupon", "these", "they", "they'd", "they'll", "they're", "they've", "thick", "thin",
            "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to",
            "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under",
            "until", "up", "upon", "us", "very", "via", "was", "wasn't", "we", "we'd",
            "well", "we'll", "were", "we're", "weren't", "we've", "what", "whatever", "what's", "when",
            "whence", "whenever", "when's", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon",
            "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "who's",
            "whose", "why", "why's", "will", "with", "within", "without", "won't", "would", "wouldn't",
            "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've");
        if (isset($_POST['searcforPRODUCTS'])) {
        $toSearchproductPost = strtolower($_POST['StringToSearch']);
        $information = array_diff(explode(" ", $toSearchproductPost), $stopwords);
        $querysub = "";
        foreach ($information as $value) {
            $querysub .= 'name LIKE "%' . $value . '%" or description LIKE "%' . $value . '%" or ';
            //echo "$value <br>";
        }
            if (strlen($querysub)==0){
                echo 'No such item can be found. Are you interested in the following items?';
                $sql = 'select * from product WHERE sold <> 1 LIMIT 50;';
            }else{
                $querysub = substr($querysub, 0, -3);
                $sql = 'select * from product WHERE sold <> 1 AND ' . $querysub . ';';
            }
        
        $retval = $conn->query($sql);
                    if ($retval->num_rows > 0) {
                        while($row = $retval->fetch_assoc())
                        {

                                $i = $row['idProduct'];
                                $toName = $i;
                                $toLike = "like". $i;
                                $toFavor = "favor". $i;
                                $toBook = "book". $i;
                                $divFinal = "saveDiv" .$i;
                                $userToChange = $userTotal['id_user'];

                        ?>
                            <div onclick="toggle(<?= $toName ?>);">
                                <?php
                                    echo "ID :{$row['name']}  <br> ";
                                ?>
                            </div>
                            
                            <div id=<?= $toName ?> style="display: none">
                                <?php
                                echo "name :{$row['name']}  <br> ".
                                    "description: {$row['description']} <br> ".
                                    "price: {$row['price']} <br> ".
                                    "Product Date : {$row['product_date']} <br> ".
                                    "--------------------------------<br>";


                                    //test if the product was liked or favorited or bokkmarked by the user

                                    $chechedLike = '';
                                    $sqlLike = "SELECT * FROM LikeT WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultLike = $conn->query($sqlLike);
                                    if ($resultLike->num_rows > 0) {
                                        $chechedLike = 'checked';
                                    }

                                    $chechedFavorite = '';
                                    $sqlFavorite = "SELECT * FROM Favorite WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultFavorite = $conn->query($sqlFavorite);
                                    if ($resultFavorite->num_rows > 0) {
                                        $chechedFavorite = 'checked';
                                    }

                                    $chechedBookmark = '';
                                    $sqlBookmark = "SELECT * FROM Bookmark WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultBookmark = $conn->query($sqlBookmark);
                                    if ($resultBookmark->num_rows > 0) {
                                        $chechedBookmark = 'checked';
                                    }
                                    ?>
                                    
                                    <form>                                
                                        Like: <input type="checkbox" name=<?= $toLike ?> value="yes" <?php echo $chechedLike; ?>>
                                        Favorite:<input type="checkbox" name=<?= $toFavor ?> value="yes" <?php echo $chechedFavorite; ?>>
                                        Bookmark:<input type="checkbox" name=<?= $toBook ?> value="yes" <?php echo $chechedBookmark; ?>>
                                        <input id="" onclick="updateAllCheckBoxValues(<?= $toName ?>,<?= $toLike ?>,
                                                                                        <?= $toFavor ?>,<?= $toBook ?>, 
                                                                                        <?= $userToChange ?>)" 
                                                                                        type="button" value="Update">                                
                                    </form >

                                    
                                    
                                   <form action="/webAppCMU/buyprodfinal.php" method="post">
                                        <input type="hidden" value=<?=$i?> name = "protobuyPOST"></input>
                                        <input type ="submit" value  = "BUY NOW!!"></input>
                                    </form>



                                    <div class="alert alert-success " role="alert" id=<?=$divFinal?>></div>
                                    <?php

                                ?>
                            </div>
                        <?php
                        } 
                    }else{
echo "There was no any product found with you search words!!\n";
                    }
                        
                        $conn->close();
				} else{
                    //se muestra lo mas actual....

                   

                }			
			?>   
</div>
    <div style="border:1px solid #000; display:inline-block; text-align:center;">

    
        <h1>Post your own stuff!!</h1>

        <form action="/webAppCMU/postProduct.php" method="post">
                Name:<br>
                    <input type="text" name="name" required>
                    <br>
                description :<br>
                    <textarea name="description" required></textarea>
                    <br>
                price :<br>
                    <input type="text" name="price" required>
                    <br>
                tags :<br>
                    <input type="text" name="tags" required>
                    <br>
                Only visible to my friends :<br>
                <input type="checkbox" name="private" value="yes">
                <br>
                <input type="submit" value="Submit Product">
        </form> 

        <?php
			
			echo $toshowProb;
			
			?>  
    </div>
</div>

</body>
</html>
