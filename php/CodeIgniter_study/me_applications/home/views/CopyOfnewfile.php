<?php 
< script >
function is_weixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}
function is_numeric(e) {
	var a = " \n\r	\f\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	return ("number" == typeof e || "string" == typeof e && a.indexOf(e.slice( - 1)) === -1) && "" !== e && !isNaN(e)
}
	
function valid_mobile(e) {
	return /^1[34578]\d{9}$/.test(e)
}
	
function valid_username(e) {
	return !! valid_mobile(e) || !is_numeric(e) && /^[a-z0-9\-_\.]{3,16}$/i.test(e)
}	
// function valid_email(e) {
// 	return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e)
// }			
function valid_showid(e) {
    if (!is_numeric(e) || -1 < e.indexOf(".") || -1 < e.toLowerCase().indexOf("e")) return ! 1;
    var a = Number(e);
    return a > 0 && a < 2147483648
}		
function htmlspecialchars(e, a, i, t) {
    var r = 0,
    n = 0,
    o = !1; ("undefined" == typeof a || null === a) && (a = 2),
    e = e.toString(),
    t !== !1 && (e = e.replace(/&/g, "&amp;")),
    e = e.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    var c = {
        ENT_NOQUOTES: 0,
        ENT_HTML_QUOTE_SINGLE: 1,
        ENT_HTML_QUOTE_DOUBLE: 2,
        ENT_COMPAT: 2,
        ENT_QUOTES: 3,
        ENT_IGNORE: 4
    };
    if (0 === a && (o = !0), "number" != typeof a) {
        for (a = [].concat(a), n = 0; n < a.length; n++) 0 === c[a[n]] ? o = !0 : c[a[n]] && (r |= c[a[n]]);
        a = r
    }
//     return a & c.ENT_HTML_QUOTE_SINGLE && (e = e.replace(/'/g, "&#039;")),
//     o || (e = e.replace(/"/g, "&quot;")),
//     e
}
Number.prototype.format = function(e) {
    void 0 === e && (e = 0);
    var a = "\\d(?=(\\d{3})+" + (e > 0 ? "\\.": "$") + ")";
    return this.toFixed(Math.max(0, ~~e)).replace(new RegExp(a, "g"), "$&,")
};
var isWeixin = is_weixin();
var winHeight = document.body.clientHeight;
if(isWeixin){
	$("#btn-recharge-weixin").removeClass("hide");
	$('.btn-recharge-ali').attr('id','btn-recharge-wxAli');
}else{
	$("#btn-recharge-weixin").addClass("hide");
}
var iSApp = new Framework7({
    sortable: !1,
    swipeout: !1,
    router: !1,
    preloadPreviousPage: !1,
    swipeBackPage: !1,
    modalTitle: "",
    modalButtonOk: "好",
    modalButtonCancel: "关闭"
}),
iSView = iSApp.addView(".view-main", {
    domCache: !0
}),
$ = Dom7;
iSApp.alipay = {},



$(document).on("click", "#btn-recharge-wxAli",function() {
	$(".statusbar-overlay").css("height",winHeight);
	$(".statusbar-overlay").css('display','block')
});

$(document).on("click", "#btn-confirm",function() {
	var e = $("#ipt-account").val().trim();
	return "" === e ? (iSApp.alert("请输入要充值的账号或ID号"), !1) : valid_username(e) || valid_email(e) || valid_showid(e) ? void $.ajax({
		url: "<?php echo site_url('partner/alipay/verify'); ?>",
		method: "PUT",
		dataType: "text",
		data: e,
		processData: !1,
		beforeSend: function() {
			iSApp.showIndicator()
		},
		complete: function() {
			iSApp.hideIndicator()
		},
		error: function(e) {
			console.log(arguments),
			iSApp.alert(e.status + " - 服务器错误，校验失败！")
		},
		success: function(a) {
			if (a = JSON.parse(a), 0 !== a.code) {
				var i = "";
				switch (a.code) {
					case 403:
						i = "未能获取到授权用户信息";
						break;
					case 404:
						i = "账号不存在";
						break;
					case 4001:
						i = "未获取到充值的账号";
						break;
					case 4002:
						i = "输入的账号不合法";
						break;
					case 5031:
						i = "5031-服务不可用";
						break;
					case 5032:
						i = "5032-账号数据异常";
						break;
					default:
						i = [a.code, a.message || "服务器错误"].join("-")
				}
				return iSApp.alert(i),
				!1
			}	
			delete a.code,
			iSApp.alipay = a,
			var timestamp=new Date().getTime(),
			localStorage.setItem("rechargeAccount_"+timestamp,iSApp.alipay.account),
			
			iSView.router.load({
				pageName: "confirm"
			})
		}
	}) : (iSApp.alert("账号或ID号输入不合法"), !1)
}).on("click", "#btn-recharge-ali",
			function() {
				var e = +$(".rdo-amount:checked").val(),
				a = 0;
				return 0 === e && (e = +$("#ipt-custom-amount").val()),
				isNaN(e) || 0 === e ? (iSApp.alert("请输入充值金额"), !1) : e < 1 ? (iSApp.alert("最少得充 1 块钱哦~"), !1) : e > 1e5 ? (iSApp.alert("最多只能充 10 万呢~"), !1) : (a = (1e3 * e).format(), void iSApp.confirm("确定要为【" + iSApp.alipay.account + "】充值" + a + "个<?php echo $conf->partner->icon_name; ?>吗？",
						function() {
							iSApp.alipay.number = a,
							$.ajax({
								url: "<?php echo site_url('partner/alipay/pay'); ?>",
								method: "PUT",
								dataType: "json",
								data: e + "\n" + iSApp.alipay.recharge + "\n" + 'alipay',
								processData: !1,
								beforeSend: function() {
									iSApp.showIndicator()
								},
								complete: function() {
									iSApp.hideIndicator()
								},
								error: function(e) {
									iSApp.alert(e.status + " - 服务器错误，充值失败！")
								},
								success: function(e) {
									if (0 !== e.code) {
										var a = "";
										switch (e.code) {
											case 403:
												a = "未能获取到授权用户信息";
												break;
											case 4001:
												a = "未获取到充值数据";
												break;
											case 4002:
												a = "未获取到充值的金额";
												break;
											case 4003:
												a = "充值金额数值不合法";
												break;
											case 4004:
												a = "充值金额只允许在1~100000之间";
												break;
											case 4005:
												a = "充值账号数据错误";
												break;
											case 4006:
											case 4007:
												a = "充值账号数据不合法";
												break;
											default:
												a = [e.code, e.message || "服务器错误"].join("-")
										}
										return iSApp.alert(a),
										!1
									}
								
									window.location.href = e.location
								}
							})
						}))
			}).on("change", ".rdo-amount",
				function() {
					var e = +$(this).val();
					0 === e ? ($("#box-custom-amount").removeClass("hide"), $("#ipt-custom-amount").focus(), $("#icon-number").text("0")) : ($("#box-custom-amount").addClass("hide"), $("#ipt-custom-amount").val(""), $("#icon-number").text((1e3 * e).format()))
				}).on("input", "#ipt-custom-amount",
						function() {
							var e = $(this),
							a = e.val().replace(/\D/g, "");
							e.val(a),
							$("#icon-number").text((1e3 * a).format())
						}),
						
						iSApp.onPageBeforeInit("success",
								function(e) {
									var a = e.container.innerHTML;
									e.container.innerHTML = a.replace("{NAME}", iSApp.alipay.account).replace("{NUMBER}", iSApp.alipay.number)
								}),
								iSApp.onPageBeforeInit("confirm",
										function(e) {
											var a = e.container.innerHTML,
											i = iSApp.alipay.avatar;
											"1000L.png" === i.substr( - 9) && (i = i.substr(0, i.length - 9) + (parseInt("<?php echo $conf->partner->ptid; ?>") + 2e3) + "L.png"),
											e.container.innerHTML = a.replace("{RECHARGE_USER}", '<img src="//userface.ispeak.cn/' + i + '">' + htmlspecialchars(iSApp.alipay.nickname, "ENT_QUOTES") + "(" + iSApp.alipay.account + ")")
										}); 
< /script>