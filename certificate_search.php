<!--Page displays all certificates in the DB, and allows the user to search for a certificate-->
<?php
    $title = "IDCP - Certificate Search";
    $page = 'certificate';
    $page_name = 'search_cert';
    require('includes/header.php');
    if(!isset($_SESSION['searchString'])){
        $_SESSION['searchString'] = "";
    }
    $searchString = $_SESSION['searchString'];
    //Allows user to reset their search
    if(isset($_SESSION['reset'])){
        unset($_SESSION['reset']);
        $searchString = "";
        $_SESSION['searchString'] = "";
    }
    # Connect to MySQL server and the database
    require( 'includes/connect_db_c9.php' ) ;
    # Includes these helper functions
    require( 'includes/certificate_helpers.php' ) ;
    if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
        $_SESSION['searchString'] = mysqli_real_escape_string($dbc, trim($_POST['searchString']));
        $page = 'certificate_search.php';
        header("Location: $page");
    }
?>
<style>
.button {
    background-color: darkred;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
<style> 
input[type=text] {
    width: 130px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    background-color: white;
    background-image: url('searchicon.png');
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 12px 20px 12px 40px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
}

input[type=text]:focus {
    width: 25%;
}
</style>
    <!-- Bread Crumbs -->
    <ol class = "breadcrumb">
        <li><a href = "home.php">Home</a></li>
        <li><a href = "certificate.php">Certificate</a></li>
        <li class = "active">Certificate Search</li>
    </ol>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="dropdown">
                    <div class="page-header">
                        <h1>Search Certificates<small> Click on a certificate to view more</small><br></h1>
                    </div>
                    <font size="3">Search a Name: &nbsp;&nbsp;</font>
                    <form method="POST" action="certificate_search.php">
                        <input type="text" name="searchString" value ="<?php echo $searchString; ?>" placeholder="Search.."/>
                        <br>
                        <br>
                        <div class = "butspan" style = "width: 200px;">
                            <button type="submit" class="btn btn-primary btn-block" style="height: 50px;">Submit</button>
                            <button type="button" id='reset' class="btn btn-secondary btn-block">Reset</button>
                        </div>
                    </form>
                    <h3>Certificates</h3>
                    <?php
                        show_brief_certificate_results($dbc, $searchString);
                    ?>
                    <br>
                    <button class="btn btn-default btn-sm" onclick ="location.href='certificate.php';">Back to Certificate Home</button>
                </div>
            </div>
            <!-- /#containter -->
        </div>
        <!-- /#page-content-wrapper -->

		<!--Footer-->
        <?php require('includes/footer.php'); ?>
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!--Makes rows clickable-->
    <script>
    jQuery(document).ready(function($) {
        $('.table > tbody > tr').click(function() {
            var value = $(this).find('td:first').text();
            jQuery.ajax({
                url: 'backendcert.php',
                type: 'POST',
                data: {
                    'CERT_NAME': value,
                },
                dataType : 'json',
                success: function(data, textStatus, xhr) {
                    window.location.href = "certificate_detail.php";
                    console.log(data); // do with data e.g success message
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(textStatus.reponseText);
                window.location.href = "certificate_detail.php";
                }
            });
        });
    });
    </script>
    <!--Resets the search and sort-->
    <script>
    jQuery(document).ready(function($) {
        $('#reset').click(function() {
            jQuery.ajax({
                 url: 'reset.php',
                type: 'POST',
                data: {
                    'reset': "reset",
                },
                dataType : 'json',
                success: function(data, textStatus, xhr) {
                    window.location.href = "certificate_search.php";
                    // alert('reset');
                    console.log(data); // do with data e.g success message
                },
                error: function(xhr, textStatus, errorThrown) {
                    // alert('reset');
                    console.log(textStatus.reponseText);
                window.location.href = "certificate_search.php";
                }
            });    
    
        });
    });
    </script>
</body>
</html>
