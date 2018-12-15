<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<!-- Modified to support service messages component.  Terry SoRelle 2018-12-14 -->
<!DOCTYPE html>
<html lang="<?php  echo Localization::activeLanguage()?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="<?php  echo $view->getThemePath()?>/css/bootstrap-modified.css">
    <?php  echo $html->css($view->getStylesheet('main.less'))?>
    <?php  Loader::element('header_required', array('pageTitle' => isset($pageTitle) ? $pageTitle : '', 'pageDescription' => isset($pageDescription) ? $pageDescription : '')); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
            var msViewportStyle = document.createElement('style')
            msViewportStyle.appendChild(
                document.createTextNode(
                    '@-ms-viewport{width:auto!important}'
                )
            )
            document.querySelector('head').appendChild(msViewportStyle)
        }
    </script>

    <!-- Styling for peanut service messages -->
    <style>
        #error-messages {
            border: 1px solid darkred;
            margin-top: 10px;
        }
        #info-message {
            border: 1px solid darkgreen;
            margin-top: 10px;
        }
        #warning-messages {
            border: 1px solid darkblue;
            margin-top: 10px;
        }
        #peanut-messages {
            position:fixed;top:0;
            z-index: 16777271;
            width:100%
        }

    </style>
</head>
<body>

<!-- Peanut service messages container -->
<div class="container" id="peanut-messages">
    <div class="row">
        <div class="col-md-12"   >
            <div id="service-messages-container"><service-messages></service-messages></div>
        </div>
    </div>
</div>;


<div class="<?php  echo $c->getPageWrapperClass()?>">
