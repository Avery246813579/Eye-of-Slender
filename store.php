<?php
setcookie('LAST_PAGE', 'index.php', time() + 3000);
?>

<!DOCTYPE html>
<html>
<?php require_once(dirname(__FILE__) . '/presets/general/RegularHeader.php'); ?>

<body class="skin-blue layout-top-nav">
<div class="wrapper">
    <?php require_once(dirname(__FILE__) . '/presets/general/RegularNavigation.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <br/>
            <br/>
            <br/>


            <div class="well">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="images/Store/IRON.png">

                            <div class="caption" style="text-align: center;">
                                <h3>Thumbnail label</h3>

                                <p>...</p>

                                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="images/Store/GOLD.png">

                            <div class="caption" style="text-align: center;">
                                <h3>Thumbnail label</h3>

                                <p>...</p>

                                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="images/Store/DIAMOND.png">

                            <div class="caption" style="text-align: center;">
                                <h3>Thumbnail label</h3>
                                <hr style = "width: 90%; color: slategray; height: 1px; background-color:#d8dada; margin-bottom: 0px; margin-top: 5px;" />

                                <p>...</p>

                                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail skin-blue">
                            <img src="images/Store/EMERALD.png">

                            <div class="caption" style="text-align: center;">
                                <h3>Thumbnail label</h3>

                                <p>...</p>

                                <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once(dirname(__FILE__) . '/presets/general/RegularFooter.php'); ?>
</div>

<?php require_once(dirname(__FILE__) . '/presets/general/RegularExternals.php'); ?>
<script src=plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src=bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src=plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src=plugins/fastclick/fastclick.min.js'></script>
<script src=dist/js/app.min.js" type="text/javascript"></script>
<script src=dist/js/demo.js" type="text/javascript"></script>
<!-- Load jQuery  -->
<script src="http://cdn.wysibb.com/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="http://cdn.wysibb.com/css/default/wbbtheme.css"/>

<!-- Init WysiBB BBCode editor -->
<script>
    $(function () {
        var wbbOpt = {
            allButtons: {
                bold: {
                    hotkey: "ctrl+shift+b"
                },
                strike: {
                    hotkey: "ctrl+shift+s"
                },
                underline: {
                    hotkey: "ctrl+shift+u"
                },
                italic: {
                    hotkey: "ctrl+shift+i"
                }
            }
        }

        $("#text-area").wysibb(wbbOpt);
        $("#post_content").htmlcode();

        $("#showmore").click(function () {
            $("#more_posts").load("/presets/profiles/ShowMore.php?times=");
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    })

</script>
</body>
</html>