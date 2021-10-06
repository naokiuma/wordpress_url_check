<?php


function curl_check($target_url, $relativePath=false)//完全なurlが入る
{
    if ($relativePath === true) {
        //相対パスチェック
        //todo
    } else {

        //404を調べたいurl
        $want_check_url = array(
            'blog.xxx-xxxx.jp',
            'xxxx-xxxx.jp',
            'xxxx-xxxx.com',
        );
        for ($i = 0; $i < count($want_check_url); $i++) {
            // echo "チェック対象:".$target_url."<br>";

            //$want_check_urlに含まれるurlはcurlで確認
            if (strpos($target_url, $want_check_url[$i]) != false) {
                $ch = curl_init($target_url);
                curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
                curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $output = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                curl_close($ch);
            
                //echo "output:" . $output."<br>";
                //echo "target_url： " . $target_url."<br>";
                //echo 'HTTP code: ' . $httpcode;
                //return $httpcode;

                if ($httpcode == 404) {
                    return $target_url;
                }
            }
        }
    }
}



$post_type = 'post'; //確認したいpost_type

$count_posts = wp_count_posts($post_type);
$posts = $count_posts->publish;//公開済みの
echo '記事数は'.$posts.'件です';

//何回ループを回すか
$roop_count = $posts /
$show_num = 50; //1回で取得する表示件数

//todo 全ての件数を回す
$args = array(
    // 'page_id' => 5,//特定の記事を指定したい場合
    'post_type' => $post_type,  // 投稿タイプ
    'posts_per_page' => $show_num, // 表示件数。 -1ならすべての投稿を取得
    'orderby' => 'date',    // ソート
                            // ・date  ：日付
                            // ・rand  ：ランダム
    'order' => 'ASC',
    'paged' => 1//何ページ目を取得するか
);    // 降順(日付の場合、日付が新しい順)


$result = [];//404のurlを詰め込む

// ループ
$the_query = new WP_Query($args);
if ($the_query->have_posts()):
    while ($the_query->have_posts()): $the_query->the_post();
        $content = $post->post_content;

        $url_pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/';//url正規表現を取得
        preg_match_all($url_pattern, $content, $matches_url, PREG_OFFSET_CAPTURE);

        // $src_pattern = '/src\s*=\s*[\"|\'](.*?)[\"|\']/i';//src正規表現を取得
        // preg_match_all($src_pattern, $content, $matches_src, PREG_OFFSET_CAPTURE);

        echo the_id();//記事のタイトル
        echo '<br>';
        echo the_title();//記事の本文
        echo '<br>';
        
        //完全なURL
        if ($matches_url) {
            foreach ($matches_url[0] as $_matches_url) {
                //echo $_matches_url[0]."<br>";//http、またはhttpsから始まる全てのurl
                $temp = curl_check($_matches_url[0]);
                if ($temp != null) {
                    array_push($result, $temp);
                }
            }
            //echo "<br>";
        }
        //echo '【src】<br>';


        //todo
        //相対パスの処理・現状取れている、がa,srcに限らず「./」から始まる相対パスのものをとる
        // if ($matches_src) {
        //     var_dump($matches);
        //     foreach ($matches_src[0] as $_matches_src) {
        //         echo htmlentities($_matches_src[0]);
        //         echo "<br>";
        //     }
        //     echo '<hr>';
        // }
        //


    endwhile;
endif;
 
// リセット(元の投稿データの復元)
wp_reset_postdata();
echo "resultです";
var_dump($result);

?>



