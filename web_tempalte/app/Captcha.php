<?php
header("Content-type: text/html; charset=utf-8");
class Captcha
{
	public function captcha()
	{
		$code = substr(str_shuffle('ABCDEFGHIJKMNPQRSTUVWXYabcdefghjkmnpqrstuvwxy23456789'), 0, 4);
		//$_SESSION('captcha', md5(strtolower($code)));
		$this->create_captcha($code, 80, 34);
	}
	
	private function create_captcha($code = '5678', $width = 50, $height = 25)
	{
		if (function_exists('imagecreatetruecolor'))
		{
			$im = imagecreatetruecolor($width, $height);
		}
		else
		{
			$im = imagecreate($width, $height);
		}
		$r = array(225, 255, 255, 223);
		$g = array(225, 236, 237, 255);
		$b = array(225, 236, 166, 125);
		$key = mt_rand(0, 3);

		$bgcolor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
		$bcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		$scolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

		imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $bgcolor);
		imagerectangle($im, 0, 0, $width - 1, $height - 1, $bcolor);

		for ($i = 0; $i < 10; $i++)
		{
			$fcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $fcolor);
		}
		for ($i = 0; $i < 25; $i++)
		{
			$fcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $fcolor);
		}

		$len = mb_strlen($code);
		$font_size = $width / $len;
		$y = $height - 5;
		for ($i = 0; $i < $len; $i++)
		{
			imagettftext($im, $font_size, mt_rand(-15, 45), $i * $font_size + 2, $y, $scolor, '../font/texb.ttf', $code{$i});
		}

		ob_clean();
		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);
	}
}

$captcha = new Captcha();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>验证码使用方式如下（直接调用captcha()即可）</title>
<meta name="description" content="">
<meta name="keywords" content="">
</head>
<body>
    验证码：<input id="captcha" name="captcha"  type="text"><img src="./Captcha/captcha" style="vertical-align:top;cursor:pointer;padding-left:20px; padding-top:5px" onclick="this.src='./Captcha/captcha?'+Math.random()">
</body>
</html>
