<?php
require_once(dirname(__FILE__) . '../../../SqlHandler.php');
require_once(dirname(__FILE__) . '../../../twitch/TwitchHandler.php');
$twitch_handler = new TwitchHandler();
$sql_handler = new SqlHandler();

$news = $sql_handler->get_news();

if (is_array($news)) {
    if (count($news) > 0) {
        $news = array_reverse($news);
        $i = 0;

        foreach ($news as $values) {
            if ($i < 4) {
                $i++;

                if ($values instanceof News) {
                    $user = $twitch_handler->get_user_using_username($sql_handler->get_account_using_id($values->account_id)->username);

                    echo '<div class="box-body">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">' . $values->title . '</h3>
                        <small class="text-muted pull-right">' . $values->written . '</small>
                    </div>

                    <div class="box-body">
                        <a href="user.php?' . $user->name . '" class="pull-left">
                            <img src="' . $user->logo . '" class="img-circle" alt="User Image" width="42" height="42" />
                        </a>

                        ' . $values->content . '
                    </div>
                </div>
            </div>';
                }
            }
        }
    }
}