# Natural Language Understanding API Samples

This directory contains sample code for using Natural Language Understanding API.

OLAMI website and documentation: [http://olami.ai](http://olami.ai)

## Run in bash:

> 1. Replace **your_php_bin** to your PHP binary path.
> 2. Replace **api_url, your_app_key, your_app_secret, your_text_input** in accordance to your needs and your own data.

```
your_php_bin QuickstartTest.php api_url your_app_key your_app_secret your_text_input
```

- For example: (Simplified Chinese Request with the text "我爱欧拉蜜")

```
/usr/bin/php QuickstartTest.php https://cn.olami.ai/cloudservice/api 172c5b7b7121407ba572da444a999999 2115d0888bd049549581b7a0a6888888 我爱欧拉蜜
```

- For example: (Traditional Chinese Request with the text "我愛歐拉蜜")

```
/usr/bin/php QuickstartTest.php https://tw.olami.ai/cloudservice/api 999888777666555444333222111000aa 111222333444555666777888999000aa 我愛歐拉蜜
```

## Run in web browser:

> 1. Add all sample PHP files onto your web server
> 2. Replace **your_host:your_port/your_web_path** to your host settings.
> 3. Replace **api_url, your_app_key, your_app_secret, your_text_input** in accordance to your needs and your own data.

```
http://your_host:your_port/your_web_path/QuickstartTest.php?url=api_url&appkey=your_app_key&appsecret=your_app_secret&inputtext=your_text_input
```

- For example: (Simplified Chinese Request with the text "我爱欧拉蜜")

```
http://localhost/QuickstartTest.php?url=https://cn.olami.ai/cloudservice/api&appkey=172c5b7b7121407ba572da444a999999&appsecret=2115d0888bd049549581b7a0a6888888&inputtext=我爱欧拉蜜
```

- For example: (Traditional Chinese Request with the text "我愛歐拉蜜")

```
http://localhost/QuickstartTest.php?url=https://tw.olami.ai/cloudservice/api&appkey=999888777666555444333222111000aa&appsecret=111222333444555666777888999000aa&inputtext=我愛歐拉蜜
```