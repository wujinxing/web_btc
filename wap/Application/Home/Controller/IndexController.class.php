<?php
namespace Home\Controller;

class IndexController extends HomeController
{
	public function index()
	{
		$indexAdver = (APP_DEBUG ? null : S('index_indexAdver'));

		if (!$indexAdver) {
			$indexAdver = M('Adver')->where(array('status' => 1))->order('id asc')->select();
			S('index_indexAdver', $indexAdver);
		}

		$this->assign('indexAdver', $indexAdver);
		$indexArticleType = (APP_DEBUG ? null : S('index_indexArticleType'));

		if (!$indexArticleType) {
			$indexArticleType = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id desc')->limit(3)->select();
			S('index_indexArticleType', $indexArticleType);
		}

		$this->assign('indexArticleType', $indexArticleType);
		$indexArticle = (APP_DEBUG ? null : S('index_indexArticle'));

		if (!$indexArticle) {
			foreach ($indexArticleType as $k => $v) {
				$indexArticle[$k] = M('Article')->where(array('type' => $v['name'], 'status' => 1, 'index' => 1))->order('id desc')->limit(6)->select();
			}

			S('index_indexArticle', $indexArticle);
		}
		
		$this->assign('indexArticle', $indexArticle);
		$indexLink = (APP_DEBUG ? null : S('index_indexLink'));

		if (!$indexLink) {
			$indexLink = M('Link')->where(array('status' => 1))->order('sort asc ,id desc')->select();
		}

		$this->assign('indexLink', $indexLink);
		

		$dataall=array();
		$k=0;
		
		foreach (C('market') as $i => $v) {
				$zhmoney=0;
				$dataall[$k][0] = $v['title'];
				$dataall[$k][1] = round($v['new_price'], $v['round']);
				$dataall[$k][2] = round($v['buy_price'], $v['round']);
				$dataall[$k][3] = round($v['sell_price'], $v['round']);
				$zhmoney=$v['volume'] * $v['new_price'];
				if($zhmoney>=10000){
					$dataall[$k][4] = (round($zhmoney/10000, 2) * 1)."万";
				}else{
					$dataall[$k][4] = round($zhmoney, 2) * 1;
				}
				$dataall[$k][5] = '';
				$dataall[$k][6] = round($v['volume'], 2) * 1;
				$dataall[$k][7] = round($v['change'], 2);
				$dataall[$k][8] = $v['name'];
				$dataall[$k][9] = '/Upload/coin/'.$v['xnbimg'];
				$dataall[$k][10] = '';
				$k++;
			}
		$this->assign('dataall',$dataall);

		$CoinList = M('Coin')->where(array('status' => 1))->select();
		$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();
		$Market = M('Market')->where(array('status' => 1))->select();
		foreach ($Market as $k => $v) {
			$Market[$v['name']] = $v;
		}
		$cny['zj'] = 0;
		foreach ($CoinList as $k => $v) {
			if ($v['name'] == 'cny') {
				$cny['ky'] = round($UserCoin[$v['name']], 2) * 1;
				$cny['dj'] = round($UserCoin[$v['name'] . 'd'], 2) * 1;
				$cny['zj'] = $cny['zj'] + $cny['ky'] + $cny['dj'];
			}
			else {
				if ($Market[C('market_type')[$v['name']]]['new_price']) {
					$jia = $Market[C('market_type')[$v['name']]]['new_price'];
				}
				else {
					$jia = 1;
				}
				$coinList[$v['name']] = array('name' => $v['name'], 'img' => $v['img'], 'title' => $v['title'] . '(' . strtoupper($v['name']) . ')', 'xnb' => round($UserCoin[$v['name']], 6) * 1, 'xnbd' => round($UserCoin[$v['name'] . 'd'], 6) * 1, 'xnbz' => round($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd'], 6), 'jia' => $jia * 1, 'zhehe' => round(($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia, 2));
				$cny['zj'] = round($cny['zj'] + (($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia), 2) * 1;
			}
		}
		$this->assign('cny', $cny);
		//添加幻灯片
		//$block_id=;
		//$list = M('Appads')->field('url,img')->where(array('block_id'=>$block_id))->order('id desc')->select();
		$list = M('Appads')->field('url,img')->order('id desc')->select();
		$this->assign('list',$list);
	
		
		C('index_html','b');
		
		if (C('index_html')) {
			$this->display('Index/' . C('index_html') . '/index');
		}
		else {
			$this->display();
		}
	}

	public function monesay($monesay = NULL)
	{
	}

	public function install()
	{
	}
}

?>