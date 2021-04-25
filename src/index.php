<?php
    session_start();
    $_SESSION=array();
    session_destroy();

	$valueOKMEssage = '';	
	if(isset($_COOKIE['registeredUSER'])) {
		$valueOKMEssage =  $_COOKIE['registeredUSER'];
		unset($_COOKIE['registeredUSER']);
		// empty value and expiration one hour before
		setcookie('registeredUSER', '', time() - 3600, "/");
	} 

?>

<!doctype html>
<html>

	<head>
		<title>Publish your own stuff!!</title>	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
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
	</head>

	<body>

		<!--First div to sign  into the system-->
		<div align="center" class="form-inline">
			<form action="/webAppCMU/verifyCredentials.php" method = "post">
				Username: 
				<input type="text" name="username">
				Password: 
				<input type="password" name="password">
				<input type="submit" value="Sign in">
			</form>
			
		</div>

		<br>

		<div style="text-align:center;">
			<div style="border:1px solid #000; display:inline-block; text-align:left;">
			<!--Div de los productos actuales-->
		
		<h1>What are you looking for?</h1>
		
		<form action="<?php $_PHP_SELF ?>" method ="POST">
			<input type ="text" name="searchProduct"  required></input>
			<input type="submit" name="searcforPRODUCTS" value="Search">
		</form>
			
			<?php
				$dbhost = 'localhost:3306';
				$dbuser = 'root';
				$dbpass = 'rootpassword';
				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
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
			if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        if (isset($_POST['searchProduct'])) {
            $toSearchproductPost = strtolower($_POST['searchProduct']);
            $information = array_diff(explode(" ", $toSearchproductPost),$stopwords);
            $querysub="";
            foreach ($information as $value) {
            $querysub .= 'name LIKE "%' . $value . '%" or description LIKE "%' . $value . '%" or ';

        }

        //echo 'string length'. strlen($querysub);
        if (strlen($querysub)==0){
            echo 'No such item can be found. Are you interested in the following items?';
            $sql = 'select * from product WHERE sold != 1  AND private_product != 1 LIMIT 50;';
        }else{
            $querysub = substr($querysub, 0, -3);
            $sql = 'select * from product WHERE (sold != 1 AND private_product != 1) AND ' . $querysub . ';';
        }

        //echo $sql;
        } else {
            $sql = 'select * from product WHERE (sold != 1 AND private_product != 1)  LIMIT 50;';
        }
        mysql_select_db('enterprisewebapp');
        $retval = mysql_query($sql, $conn);
        if (!$retval) {
            die('Could not get data: ' . mysql_error());
        }
        $i = 0;
				while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
				{
					$toName = "product" + $i;
					?>
						<div class="panel panel-primary" onclick="toggle(<?= $toName ?>);">
							<?php
								echo "ID :{$row['name']}  <br> ";
							?>
						</div>
						
						<div id=<?=$toName?> style="display: none">
							<?php
							echo "name :{$row['name']}  <br> ".
                                "description: {$row['description']} <br> ".
                                "price: {$row['price']} <br> ".
                                "Product Date : {$row['product_date']} <br> ".
                                "--------------------------------<br>";
							?>
						</div>
					<?php
					$i++;
					
				} 

				if($i === 0){
					echo "There was no any product found with you search words!!\n";
				}
				
				mysql_close($conn);
			?>
		</div>



		<div style="border:1px solid red; display:inline-block;">
		<!--Div de poner si se quiere registrar para realizar mas acciones en el sistema-->
			<h1>Do you want to sell or buy a product?</h1>

			<h1>Register Here: </h1> <button onclick="toggle('registerForm')">REGISTER</button>

			<?php echo $valueOKMEssage;?>
			<!--Div que se oculta y se muestra solo cuando se quiere registrar-->
			<div id = "registerForm" style="display: none">
				<form action="/webAppCMU/register.php" method="post">
					Name:<br>
						<input type="text" name="name" required>
						<br>
					username (Nickname) :<br>
						<input type="text" name="username" required>
						<br>
					password :<br>
						<input type="password" name="password" required>
						<br>
					e-mail :<br>
						<input type="text" name="email" required>
						<br>
					Telephone :<br>
						<input type="text" name="telephone" required>
						<br>
					Address :<br>
						<input type="text" name="address" required>
						<br>
					<input type="submit" value="Submit" required>
				</form> 
				
			</div>
		</div>
		
		</body>
</html>