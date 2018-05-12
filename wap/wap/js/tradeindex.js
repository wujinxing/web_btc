var market="{$market}";
    var market_round="{$C['market'][$market]['round']}";
    var market_round_num=8-"{$C['market'][$market]['round']}";
	var userid="{$Think.session.userId}";
	var trade_moshi=1;
	var getDepth_tlme=null;
	var trans_lock = 0;
	
								function myFunction(){
									$("#buy_mum").html(($('#buy_num').val()*$('#buy_best_price').val()).toFixed(8)*1);
								}
								function myFunctiony(){
									$("#sell_mum").html(($('#sell_best_price').val()*$('#sell_num').val()).toFixed(8)*1);
								}
								function szwom(){
									var buy_numo=document.getElementById('buy_num').value;
									if(buy_numo==0){
										document.getElementById('buy_num').value='';
									}
								}
								function szwo(){
									var sell_numo=document.getElementById('sell_num').value;
									if(sell_numo==0){
										document.getElementById('sell_num').value='';
									}
								}
	
function tradeadd_buy(){
		var price=parseFloat($('#buy_best_price').val());
		var num=parseFloat($('#buy_num').val());
		var paypassword=$('#buy_paypassword').val();
		if(price==""||price==null){
			alert('请输入购买价格！');
			return false;
		}
		if(num==""||num==null||num==0){
			alert('请输入购买数量！');
			return false;
		}
		//加载层-风格3
		layer.load(2);
		//此处演示关闭
		setTimeout(function(){
		  layer.closeAll('loading');
		}, 10000);
		$.post("{:U('Trade/upTrade')}",{price : price,num : num,paypassword :paypassword,market : market,type : 1 },function(data){
			if(data.status==1){
				//查询是否勾选记住交易密码
		$.post('/user/uptpwdsetting', {paypassword : paypassword,tpwdsetting : 3}, function(d){
	             if(d.status) {
	             } else {
                 }
	         },'json');
				alert(data.info);
			}else{
				alert(data.info);
			}
			window.location=window.location.href;
		},'json');
	}
	function getJsonData(){
		$.getJSON("/Chart/getJsonData?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data[0]){
					$("#marketbuylist").html(data[0]);
				}
				if(data[1]){
					$("#marketselllist").html(data[1]);
				}
				if(data[2]){
					$("#marketorderlist").html(data[2]);
				}
			}
		});
	}
	getJsonData();
	// 倒计时
	var wait=second=5;
	var go=setInterval(function(){
		wait--;
		if(wait<0){
			getJsonData();
			wait=second;
		}
	},1000);
	function tradeadd_sell(){
		var price=parseFloat($('#sell_best_price').val());
		var num=parseFloat($('#sell_num').val());
		var paypassword=$('#sell_paypassword').val();
		if(price==""||price==null){
			alert('请输入卖出价格！');
			return false;
		}
		if(num==""||num==null){
			alert('请输入卖出数量！');
			return false;
		}
		layer.load(2);
		//此处演示关闭
		setTimeout(function(){
		  layer.closeAll('loading');
		}, 10000);
		$.post("{:U('Trade/upTrade')}",{price :price,num : num,paypassword : paypassword,market : market,type : 2 },function(data){
			layer.closeAll('loading');
			if(data.status==1){
				$.post('/user/uptpwdsetting', {paypassword : paypassword,tpwdsetting : 3}, function(d){
	             if(d.status) {
	             } else {
                 }
	         },'json');
				alert(data.info);
			}else{
				alert(data.info);
			}
			window.location=window.location.href;
		},'json');
	}
$.getJSON("/Ajax/getDepth?market="+market+"&trade_moshi="+trade_moshi+"&t="+Math.random(),function(data){
				if(data){
					if(data['depth']){
						var list='';
						var sellk=data['depth']['sell'].length;
						if(data['depth']['sell']){
							for(i=0; i<data['depth']['sell'].length; i++){
								list+='<li class="clear" ><span class="w_94 green">卖'+(sellk-i)
								+'</span><span class="w_175">￥'+data['depth']['sell'][i][0]+'</span><span class="w_175">'
								+data['depth']['sell'][i][1]+'</span></li>';
							}
						}
						
						$("#salelist").html(list);
						list2='';
						if(data['depth']['buy']){
							for(i=0; i<data['depth']['buy'].length; i++){
								list2+='<li class="clear" ><span class="w_94 red">买'+(i+1)
								+'</span><span class="w_175">￥'+data['depth']['buy'][i][0]+'</span><span class="w_175">'
								+data['depth']['buy'][i][1]+'</span></li>';
							}
						}
						$("#buylist").html(list2);
					}

				}
			});
		$.getJSON("/Ajax/getTradelog?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data['tradelog']){
					var list='';
					var type='';
					var typename='';
					for( var i in data['tradelog']){
						if(data['tradelog'][i]['type']==1){
							list+='<dd class="clear"><span class="w_180 red">买入</span><span class="w_270">'+data['tradelog'][i]['oktime']+'</span><span class="w_270">'+data['tradelog'][i]['price']+'</span><span class="w_270 po_re red">'+data['tradelog'][i]['num']+'</span></dd>';
						}else{
							list+='<dd class="clear"><span class="w_180 green">卖出</span><span class="w_270">'+data['tradelog'][i]['oktime']+'</span><span class="w_270">'+data['tradelog'][i]['price']+'</span><span class="w_270 po_re green">'+data['tradelog'][i]['num']+'</span></dd>';
						}
					}
					$("#orderlist").html(list);
				}
			}
		});
$.getJSON("/Ajax/getEntrustAndUsercoin?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data['entrust']){
					$('#entrust_over').show();
					var list='';
					var cont=data['entrust'].length;
					for(i=0; i<data['entrust'].length; i++){
						
						if(data['entrust'][i]['type']==1){
							list+='<dt class="clear"><span class="w_180 red">买</span><span class="w_270">'+data['entrust'][i]['price']+'</span><span class="w_180">'+data['entrust'][i]['num']+'</span><span class="w_180">'+data['entrust'][i]['deal']+'</span><span class="w_180"><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);">撤销</a></span></dt>';
						}else{
							list+='<dt class="clear"><span class="w_180 green">卖</span><span class="w_270">'+data['entrust'][i]['price']+'</span><span class="w_180">'+data['entrust'][i]['num']+'</span><span class="w_180">'+data['entrust'][i]['deal']+'</span><span class="w_180"><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);">撤销</a></span></dt>';
						}
					}
					$('#entrustlist').html(list);
				}else{
					$('#entrustlist').html("<div class='center record_no'>暂无记录</div>");
				}
				var alldb=0;
				if(data['usercoin']){
					if(data['usercoin']['xnb']){
						$("#my_xnb").html('฿ '+data['usercoin']['xnb']);
					}else{
						$("#my_xnb").html('฿ 0.00');
					}
					if(data['usercoin']['xnbd']){
						$("#my_xnbd").html('฿ '+data['usercoin']['xnbd']);
					}else{
						$("#my_xnbd").html('฿ 0.00');
					}
					if(data['usercoin']['allxnb']){
						$("#my_allxnb").html('฿ '+data['usercoin']['allxnb']);
					}else{
						$("#my_allxnb").html('฿ 0.00');
					}
				}
			}
		});
		
		function cancelaa(id){
		$.post("{:U('Trade/chexiao')}",{id : id },function(data){
			if(data.status==1){
				getEntrustAndUsercoin();
				alert(data.info);
			}else{
				alert(data.info);
			}
		});
	}
	function getEntrustAndUsercoin(){
		$.getJSON("/Ajax/getEntrustAndUsercoin?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data['entrust']){
					$('#entrust_over').show();
					var list='';
					var cont=data['entrust'].length;
					for(i=0; i<data['entrust'].length; i++){
						if(data['entrust'][i]['type']==1){
							list+='<dt class="clear"><span class="w_180 red">买</span><span class="w_270">'+data['entrust'][i]['price']+'</span><span class="w_180">'+data['entrust'][i]['num']+'</span><span class="w_180">'+data['entrust'][i]['deal']+'</span><span class="w_180"><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);">撤销</a></span></dt>';
						}else{
							list+='<dt class="clear"><span class="w_180 green">卖</span><span class="w_270">'+data['entrust'][i]['price']+'</span><span class="w_180">'+data['entrust'][i]['num']+'</span><span class="w_180">'+data['entrust'][i]['deal']+'</span><span class="w_180"><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);">撤销</a></span></dt>';
						}
					}
					$('#entrustlist').html(list);
				}else{
					$('#entrustlist').html("<div class='center record_no'>暂无记录</div>");
				}
				var alldb=0;
				if(data['usercoin']){
					if(data['usercoin']['xnb']){

						$("#my_xnb").html('¥ '+data['usercoin']['xnb']);
					}else{
						$("#my_xnb").html('¥ 0.00');
					}
					if(data['usercoin']['xnbd']){
						$("#my_xnbd").html('¥ '+data['usercoin']['xnbd']);
					}else{
						$("#my_xnbd").html('¥ 0.00');
					}
					if(data['usercoin']['allxnb']){
						$("#my_allxnb").html('¥ '+data['usercoin']['allxnb']);
					}else{
						$("#my_allxnb").html('¥ 0.00');
					}
				}
			}
		});
		}
    function getJsonTop() {
        $.getJSON("/Ajax/getJsonTop?market={$market}&t=" + Math.random(), function (data) {
            if (data) {
                if (data['info']['new_price']) {
                    $('#market_new_price').removeClass('buy');
                    $('#market_new_price').removeClass('sell');
                    if ($("#market_new_price").html() > data['info']['new_price']) {
                        $('#market_new_price').addClass('sell');
                    }
                    if ($("#market_new_price").html() < data['info']['new_price']) {
                        $('#market_new_price').addClass('buy');
                    }
                    $("#market_new_price").html(data['info']['new_price']);
                }
                if (data['info']['buy_price']) {
                    $('#market_buy_price').removeClass('buy');
                    $('#market_buy_price').removeClass('sell');
                    if ($("#market_buy_price").html() > data['info']['buy_price']) {
                        $('#market_buy_price').addClass('sell');
                    }
                    if ($("#market_buy_price").html() < data['info']['buy_price']) {
                        $('#market_buy_price').addClass('buy');
                    }
                    $("#market_buy_price").html(data['info']['buy_price']);
					$("#cnentermoney").html("￥ "+data['info']['buy_price']);
                    $("#sell_best_price").val(data['info']['buy_price']);
                }
                if (data['info']['sell_price']) {
                    $('#market_sell_price').removeClass('buy');
                    $('#market_sell_price').removeClass('sell');
                    if ($("#market_sell_price").html() > data['info']['sell_price']) {
                        $('#market_sell_price').addClass('sell');
                    }
                    if ($("#market_sell_price").html() < data['info']['sell_price']) {
                        $('#market_sell_price').addClass('buy');
                    }
                    $("#market_sell_price").html(data['info']['sell_price']);
                    $("#buy_best_price").val(data['info']['sell_price']);
                }
                if (data['info']['max_price']) {
                    $("#market_max_price").html(data['info']['max_price']);
                }
				if (data['info']['all_price']) {
                    $("#market_all_price").html(data['info']['all_price']);
                }
                if (data['info']['min_price']) {
                    $("#market_min_price").html(data['info']['min_price']);
                }
                if (data['info']['volume']) {
                    if (data['info']['volume'] > 10000) {
                        data['info']['volume'] = (data['info']['volume'] / 10000).toFixed(2) + "万"
                    }
                    if (data['info']['volume'] > 100000000) {
                        data['info']['volume'] = (data['info']['volume'] / 100000000).toFixed(2) + "亿"
                    }
                    $("#market_volume").html(data['info']['volume']);
                }
                if (data['info']['change']) {
                    $('#market_change').removeClass('buy');
                    $('#market_change').removeClass('sell');
                    if (data['info']['change'] > 0) {
                        $('#market_change').addClass('buy');
                    } else {
                        $('#market_change').addClass('sell');
                    }
                    $("#market_change").html(data['info']['change'] + "%");
                }
                if (data['list']) {
                    var list = '';
                    for (var i in data['list']) {
                        list += '<li><a href="/Trade/index/market/' + data['list'][i]['name'] + '"> <img src="__UPLOAD__/coin/' + data['list'][i]['img'] + '" style="width: 40px; float: left; margin-right: 5px;"> <span class="all_coin_name"><p>' + data['list'][i]['title'] + '</p> <span id="all_coin_' + data['list'][i]['name'] + '">' + data['list'][i]['new_price'] + '</span></span></a></li>';
                    }
                    $("#all_coin").html(list);
                }


            }
        });
    }
    $(function () {
        getJsonTop();
        $('.all_coin_price').hover(function () {
            $('.all_coin_list').show()
        }, function () {
            $('.all_coin_list').hide()
        });
    });