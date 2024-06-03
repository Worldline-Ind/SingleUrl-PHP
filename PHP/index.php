<?php
$current_page_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

function check_extension($ext)
{
    $extIn = extension_loaded($ext);
    if ($extIn) {
        return true;
    } else {
        return false;
    }
}

function check_ssl_version()
{
    $ssl_version =  explode(" ", OPENSSL_VERSION_TEXT);
    $ssl_version_1 = (float)$ssl_version[1];
    if ($ssl_version_1 < 1.0) {
        return false;
    } else {
        return true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        #main-div {
            width: 30%;
            display: flex;
            margin: 0 auto;
            margin-bottom: 20px;
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        .fa-check-circle {
            color: green;
        }

        .fa-times-circle {
            color: red;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>
    <div id="main-div">
        <table width="100%" border="1" cellpadding="2" cellspacing="0">
            <tr>
                <td colspan="2">
                    <center>Requirement Checklist</center>
                </td>
            </tr>
            <tr>
                <td>Open SSL</td>
                <td><?php
                    if (check_ssl_version()) {
                        echo '<i class="fas fa-check-circle"></i>';
                    } else {
                        echo '<i class="fas fa-times-circle"></i>';
                    };
                    ?></td>
            </tr>
            <!-- <tr>
                <td>YAML Extension</td>
                <td><?php
                    /*if (check_extension('yaml')) {
                        echo '<i class="fas fa-check-circle"></i>';
                    } else {
                        echo '<i class="fas fa-times-circle"></i>';
                    };*/
                    ?></td>
            </tr> -->
            <tr>
                <td>SOAP Module</td>
                <td><?php
                    if (check_extension('soap')) {
                        echo '<i class="fas fa-check-circle"></i>';
                    } else {
                        echo '<i class="fas fa-times-circle"></i>';
                    };
                    ?></td>
            </tr>
            <tr>
                <td> Your PHP Version </td>
                <td><b><?php echo phpversion(); ?></b></td>
            </tr>
            <tr>
                <td colspan="2"><button><a href="<?php echo $current_page_url; ?>">Reload</a></button></td>
            </tr>
        </table>
    </div>
</body>

</html>