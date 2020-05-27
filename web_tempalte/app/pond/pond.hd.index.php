<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="client-verification" content="client_signup_page_ok">
<meta name="client-verification" content="client_page_ok">
<meta name = "format-detection" content = "telephone=no,email=no,address=no">
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
<link rel="icon" href="<?php echo base_url('favicon.ico') ?>">
<link rel="shortcut icon" href="<?php echo base_url('favicon.ico') ?>">
<title>幸运奖池</title>
<script>(self.window!==top.window)&&(top.window.location.href=self.window.location.href),!function(a){function b(){var a=e.getBoundingClientRect().width,b=a/10;e.style.fontSize=b+"px";try{"function"==typeof eval("init")&&init()}catch(e){}}var c,d=a.document,e=d.documentElement;a.addEventListener("resize",function(){clearTimeout(c),c=setTimeout(b,300)},!1),b()}(window);
</script>
<link rel="stylesheet" href="<?php echo base_url('static/template/library/pond.index.min.css?t='.time()); ?>"/>
</head>
<body>
	<div class="container">
		<div class="bannerbox">
			<!-- <img class="banner" src="<?php echo base_url('static/template/images/banner@2x.png')?>">	 -->
		</div>
		<div class='rules_tips'>
			<p>1、用户每送1个“水雷”，礼物价格的35%计入总奖池</p>
			<p>2、总奖池分为用户奖池和主播奖池，其中用户奖池的比例约为64%，主播奖池约为36%</p>
			<p>3、用户赠送水雷触发爆奖时，该用户和对应的主播获得奖池中的全部奖励</p>
		</div>
		<div class="pondbox">
			<div class="luckypond">
				<div class="pondtitle">
					<!-- <img src="<?php echo base_url('static/template/images/luckpond@2x.png')?>" class="titleimg"> -->
				</div>
				<div class="pondnum">
					<div class="pondsmalltitle">用户奖池（钻石）</div>
					<ul class="userpond pondcontainer">
						<?php
							if(isset($data['pool']))
							{
								$userpool = $data['pool']['userpool'];
								$num = count($userpool);
								for($i =0; $i<8; $i++)
								{
									if($i < (8-$num))
									{
										echo '<li>0</li>';
									}
									elseif($i>=(8-$num) && $i<8)
									{
										echo '<li>',$userpool[$i-(8-$num)],'</li>';
									}
								}
							}
							else
							{
								for($i =0; $i<8; $i++)
								{
									echo '<li>0</li>';
								}
							}
						?>
						<div class="clear"></div>
					</ul>
				</div>
				<div class="anchorbox">
					<div class="pondsmalltitle">主播奖池（经验）</div>
					<ul class="anchorpond pondcontainer">
						<?php
							if(isset($data['pool']))
							{
								$anchorpool = $data['pool']['anchorpool'];
								$num = count($anchorpool);
								for($i =0; $i<8; $i++)
								{
									if($i < (8-$num))
									{
										echo '<li>0</li>';
									}
									elseif($i>=(8-$num)&& $i<8)
									{
										echo '<li>',$anchorpool[$i-(8-$num)],'</li>';
									}
								}
							}
							else
							{
								for($i =0; $i<8; $i++)
								{
									echo '<li>0</li>';
								}
							}
						?>
						<div class="clear"></div>
					</ul>
				</div>
			</div>
		</div>
		<div class="control">
			<a href="javascript:;" class="title title1 active" data-titleid="1">幸运大奖得主</a>
			<a href="javascript:;" class="title title2" data-titleid="2">主播榜</a>
			<a href="javascript:;" class="title title3" data-titleid="3">富豪榜</a>
		</div>
		<div class="footerbox">
			<div class='winnerlist'>
				<table class="tableheader">
					<tr>
						<td width="200px">时间</td>
						<td>获奖用户</td>
						<td class="winnertablewidth">用户奖励</td>
						<td>幸运主播</td>
						<td class="winnertablewidth">主播奖励</td>
					</tr>
				</table>
				<div class="gundongtiao">
				<?php if(isset($error)): ?>
					<table class="tablelist">
						<tr><td colspan="5" class="empty-tbody"><?php echo $error;?></td></tr>
					</table>
				<?php elseif(isset($data['rowss']) && count($data['rowss'])>0):?>
					<table class="tablelist">
						<?php foreach($data['rowss'] as &$value): ?>
						<tr>
							<td><?php echo $value['dates'];?></td>
							<td><?php echo htmlspecialchars($value['sendernickname'], ENT_IGNORE ,'UTF-8');?></td>
							<?php echo sprintf('<td class="winnertablewidth">%s</td>', $value['rewardpoint'], $value['rewardpoint']); ?>
							<td><?php echo htmlspecialchars($value['accepternickname'], ENT_IGNORE ,'UTF-8');?></td>
							<?php echo sprintf('<td class="winnertablewidth">%s</td>', $value['topint'], $value['topint']);?>
						</tr>
						<?php endforeach;?>
					</table>
				<?php else:?>
					<p class='no-data'>还没有幸运得主！</p>
				<?php endif;?>
				</div>
			</div>
			<div class="content  display">
				<p class="header">
					<span class="head-rank">排名</span>
					<span class="head-nickname">昵称</span>
					<span class="head-gift">经验</span>
				</p>
				<div class="rank-data">
					<?php
					if (isset($res) && !empty($re))
					{
						foreach ($res as $key => $val)
						{
					?>
							<p class="li">
								<span class="li-rank">
								<?php if($key<3):?>
								<img src="<?php echo $weburl . '/' . ($key + 1);?>.png" />
								<?php else:?>
								<span style="color:#ffffff;"><?php echo $key + 1;?></span>
								<?php endif;?>
								</span>
								<span class="li-info">
									<?php if($key<3):?>
										<img class="face-icon" src="<?php echo $userface_address . $val['icon'];?>" />
									<?php endif;?>
									<span class="nickname <?php if($key>=3): echo ' no_face';endif;?>">
									<?php
									if (isset($val['level']) && $val['level'] > 0)
									{
										echo '<img class="level-icon" src="' . $weburl . '/level_icon/rank_' . $val['level'] . '.png?170823" />';
										echo htmlspecialchars($val['nickname']);
									}else{
										echo '<span style="padding-left: 10px;">'.htmlspecialchars($val['nickname']).'</span>';
									}
									?>
								</span>
								</span>
								<span class="li-gift<?php if($key>=3): echo ' no_face';endif;?>"><?php echo $val['total'];?></span>
							</p>
					<?php
						}
					}
					else
					{
					?>
						<p class="no-data">暂无数据</p>
					<?php
					}
					?>
				</div>
				<p class="cut-line">主播当前排名</p>
				<div class="rank-self">
					<?php
					if(isset($self) && !empty($self) && 0 < $self['self_total'])
					{
					?>
						<p class="li self-info">
							<span class="li-rank"><span style="color:#ffffff;"><?php echo $self['self_rank'] > 99 ? '99+' : $self['self_rank'];?></span></span>
							<span class="li-info">
								<span class="nickname">
									<?php
									if (isset($self['level']) && $self['level'] > 0)
									{
										echo '<img class="level-icon" src="' . $weburl . '/level_icon/rank_' . $self['level'] . '.png?170823" />';
									}
									echo htmlspecialchars($self['nickname']);
									?>
								</span>
							</span>
							<span class="li-gift"><?php echo $self['self_total'];?></span>
						</p>
					<?php
					}
					else
					{
					?>
						<p class="no-rank">未上榜</p>
					<?php
					}
					?>
				</div>
			</div>
		</div>

		<!-- 倒计时 -->
		<div class="timer">
			<div class="day">05天</div>
			<div class="remain-time">
				<span class="hour">12</span>:<span class="minute">12</span>:<span class="second">12</span>
			</div>
			<div class="clear"></div>
		</div>
		<div class="refresh">00</div>
	</div>
	<div class="cover"></div>
	<script src="//cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
	<script src="<?php echo base_url('static/template/library/layer/layer.js'); ?>?3.0" merge="true"></script>
	<script>
	+function(a){a.htmlspecialchars=function(a,b,c,d){var e=0,f=0,g=!1;("undefined"==typeof b||null===b)&&(b=2),a=a.toString(),d!==!1&&(a=a.replace(/&/g,"&amp;")),a=a.replace(/</g,"&lt;").replace(/>/g,"&gt;");var h={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(0===b&&(g=!0),"number"!=typeof b){for(b=[].concat(b),f=0;f<b.length;f++)0===h[b[f]]?g=!0:h[b[f]]&&(e|=h[b[f]]);b=e}return b&h.ENT_HTML_QUOTE_SINGLE&&(a=a.replace(/'/g,"&#039;")),g||(a=a.replace(/"/g,"&quot;")),a}}(Zepto);var WEBURL="<?php echo $weburl;?>",USERFACE_ADDRESS="<?php echo $userface_address;?>",AID=parseInt("<?php echo $aid;?>"),TITLEID=1,bdate=parseInt("<?php echo $bdate; ?>"),edate=parseInt("<?php echo $edate; ?>"),now=parseInt("<?php echo $now; ?>"),$=Zepto;show_count_down();var count_down_timer=window.setInterval("show_count_down()",1000);function show_count_down(){if(stop_count_down(now,bdate,edate)){return}var remain_second=edate-now;var day=Math.floor(remain_second/(60*60*24))+'',day=day.length==2?day:'0'+day;var hour=Math.floor((remain_second-day*24*60*60)/3600)+'',hour=hour.length==2?hour:'0'+hour;var minute=Math.floor((remain_second-day*24*60*60-hour*3600)/60)+'',minute=minute.length==2?minute:'0'+minute;var second=Math.floor(remain_second-day*24*60*60-hour*3600-minute*60)+'',second=second.length==2?second:'0'+second;$('.day').text(day+'天');$('.hour').text(hour);$('.minute').text(minute);$('.second').text(second);now=parseInt(now)+1}function stop_count_down(now,bdate,edate){if(now>=edate||now<=bdate){clearInterval(count_down_timer);if(now<=bdate){$('.day').text('03')}else{$('.day').text('00')}$('.hour').text('00');$('.minute').text('00');$('.second').text('00');return true}}$(document).on("tap click","a.title",function(){var titleid=$(this).data("titleid"),active_titleid=$(".active").data("titleid");if(titleid==active_titleid)return false;$(".active").css({"background-image":""});$(this).css({"background-image":"url(<?php echo base_url('static/template/images/button2@2x.png');?>)"});$(".active").removeClass("active");$(this).addClass("active");if(titleid==1){$(".refresh").show();refresh(10);clearInterval(timer);timer=setInterval("refresh(refresh_time)",1000);$(".winnerlist").show();$(".content").hide()}else{TITLEID=titleid;$(".winnerlist").hide();$(".content").show();$(".refresh").show();refresh(10);clearInterval(timer);timer=setInterval("refresh(refresh_time)",1000);var cut_word=titleid==2?'主播':'您',gift_word=titleid==2?'经验':'钻石';$(".content").html('<p class="header"><span class="head-rank">排名</span><span class="head-nickname">昵称</span><span class="head-gift">'+gift_word+'</span></p><div class="rank-data"></div><p class="cut-line">'+cut_word+'当前排名</p><div class="rank-self"></div>');parse_data(titleid,1);parse_pond_data(1)}});function parse_data(titleid,loading){$.ajax({url:"<?php echo site_url('index.php/pond/rank'), '?conke=', rawurlencode($conke);?>",data:{titleid:titleid,aid:AID},dataType:'json',type:'post',beforeSend:function(){if(loading==1){clearInterval(data_timer);$(".cover").show()}},complete:function(){if(loading==1){data_timer=window.setInterval("check_data_timer()",11000);$(".cover").hide()}},success:function(res){if(res.code==0){var data=res.data,self=data.length,HTML='';if(1==titleid){if(self>0){$.each(data,function(i,item){HTML+='<tr>';HTML+='<td>'+item.dates+'</td>';HTML+='<td>';HTML+=item.sendernickname;HTML+='</td>';HTML+='<td>'+item.rewardpoint+'</td>';HTML+='<td>';HTML+=item.accepternickname;HTML+='</td>';HTML+='<td>'+item.topint+'</td>';HTML+='</tr>'});$(".tablelist").html(HTML)}else{$(".tablelist").html('<tr><td colspan="5" class="empty-tbody">还没有幸运得主！</td></tr>')}}else{var data=res.data,self=res.self,HTML='';$.each(data,function(i,item){HTML+='<p class="li">';if(i<3){HTML+='<span class="li-rank"><img src="'+WEBURL+'/'+(i+1)+'.png" /></span>'}else{HTML+='<span class="li-rank"><span style="color:#ffffff;">'+(i+1)+'</span></span>'}HTML+=' <span class="li-info">';if(i<3){HTML+='<img class="face-icon" src="'+USERFACE_ADDRESS+item['icon']+'" /> ';HTML+='<span class="nickname">'}else{HTML+='<span class="nickname no_face">'}if(item['level']>0){HTML+='<img class="level-icon" src="'+WEBURL+'/level_icon/rank_'+item['level']+'.png?170823" />';HTML+=$.htmlspecialchars(item['nickname'],"ENT_IGNORE")}else{HTML+='<span style="padding-left: 10px;">'+$.htmlspecialchars(item['nickname'],"ENT_IGNORE")+'</span>'}HTML+='</span>';HTML+='</span>';if(i<3){HTML+='<span class="li-gift">'+item['total']+'</span>'}else{HTML+='<span class="li-gift no_face">'+item['total']+'</span>'}HTML+='</p>'});$(".rank-data").html(HTML);if(self.self_total>0){var SELF_HTML='';SELF_HTML+='<p class="li self-info">';SELF_HTML+='<span class="li-rank"><span style="color:#ffffff;">'+(self.self_rank>99?'99+':self.self_rank)+'</span></span>';SELF_HTML+='<span class="li-info">';SELF_HTML+='	<span class="nickname">';if(self.level>0){SELF_HTML+='	<img class="level-icon" src="'+WEBURL+'/level_icon/rank_'+self.level+'.png?170823" />'}SELF_HTML+=$.htmlspecialchars(self.nickname,"ENT_QUOTES");SELF_HTML+='	</span>';SELF_HTML+='</span>';SELF_HTML+='<span class="li-gift">'+self.self_total+'</span>';SELF_HTML+='</p>';$(".rank-self").html(SELF_HTML)}else{$(".rank-self").html('<p class="no-rank">未上榜</p>')}}}else if(res.code==4040){$(".rank-data").html('<p class="no-data">暂无数据</p>');$(".rank-self").html('<p class="no-rank">未上榜</p>')}else{dia(res.message+'-'+res.code);return false}}})}function parse_pond_data(loading){$.ajax({url:"<?php echo site_url('index.php/pond/pool'), '?conke=', rawurlencode($conke);?>",dataType:'json',type:'post',beforeSend:function(){if(loading==1){clearInterval(data_timer);$(".cover").show()}},complete:function(){if(loading==1){data_timer=window.setInterval("check_data_timer()",11000);$(".cover").hide()}},success:function(res){var userHtml='',anchorHtml='';if(res.code==0){var data=res.data,self=res.self,userPool=data['userpool'],anchorPool=data['anchorpool'],userLength=userPool.length,anchorLength=anchorPool.length;if(userLength>0){for(var i=0;i<8;i++){if(i<(8-userLength)){userHtml+='<li>0</li>'}else if(i>=(8-userLength)&&i<8){userHtml+='<li>'+userPool[i-(8-userLength)]+'</li>'}}$(".userpond").html(userHtml)}else{for(var i=0;i<8;i++){userHtml+='<li>0</li>'}$(".userpond").html(userHtml)}if(anchorLength>0){for(var i=0;i<8;i++){if(i<(8-anchorLength)){anchorHtml+='<li>0</li>'}else if(i>=(8-anchorLength)&&i<8){anchorHtml+='<li>'+anchorPool[i-(8-anchorLength)]+'</li>'}}$(".anchorpond").html(anchorHtml)}else{for(var i=0;i<8;i++){anchorHtml+='<li>0</li>'}$(".anchorpond").html(anchorHtml)}}else{for(var i=0;i<8;i++){userHtml+='<li>0</li>'}$(".userpond").html(userHtml);for(var i=0;i<8;i++){anchorHtml+='<li>0</li>'}$(".anchorpond").html(anchorHtml)}}})}var refresh_time=10;refresh(refresh_time);var timer=window.setInterval("refresh(refresh_time)",1000);function refresh(time){if(now>=edate||now<=bdate){clearInterval(timer);$('.refresh').text('00');return}var text=time+'',text=text.length==2?text:'0'+text;$('.refresh').text(text);refresh_time=time-1;if(refresh_time<0){refresh_time=10}}var data_timer=window.setInterval("check_data_timer()",11000);function check_data_timer(){if(now>=edate){return}parse_data(TITLEID,2);parse_pond_data(2)}function dia(message){layer.open({content:'<p class="layer-loading"></p>'+message,time:1.5})}
	</script>
</body>
</html>
