# wordpress_url_check
記事の中にurlがある場合、404かどうかを確認するプログラム

## チェックしたいurlを下記のように入れる。

//404を調べたいurl<br>
$want_check_url = array(<br>
'google.com',<br>
'www.amazon.co.jp',<br>
);<br>

## 参考記事
正規表現の話。<br>
https://marunouchi-tech.i-studio.co.jp/350/<br>
curlでステータスコードを取得<br>
https://qiita.com/Nelson605/items/b9f6112129c908e01276<br>
