<?php
namespace Home\Controller;

class LoginController extends HomeController
{
	public function register()
	{
		$this->display();
	}

	public function upregister($username, $password, $repassword, $verify, $invit, $moble, $moble_verify)
	{
		if (!check_verify(strtoupper($verify))) {
			$this->error('图形验证码错误!');
		}

		if (!check($username, 'username')) {
			$this->error('用户名格式错误！');
		}

		if (!check($password, 'password')) {
			$this->error('登录密码格式错误！');
		}

		if ($password != $repassword) {
			$this->error('确认登录密码错误！');
		}
		
		if (!check($moble, 'moble')) {
			$this->error('手机号码格式错误！');
		}

		if (!check($moble_verify, 'd')) {
			$this->error('短信验证码格式错误！');
		}

		if ($moble_verify != session('real_verify')) {
			$this->error('短信验证码错误！');
		}

		if (M('User')->where(array('moble' => $moble))->find()) {
			$this->error('手机号码已存在！');
		}

		if (M('User')->where(array('username' => $username))->find()) {
			$this->error('用户名已存在');
		}

		if (!$invit) {
			$invit = session('invit');
		}

		$invituser = M('User')->where(array('invit' => $invit))->find();

		if (!$invituser) {
			$invituser = M('User')->where(array('id' => $invit))->find();
		}

		if (!$invituser) {
			$invituser = M('User')->where(array('username' => $invit))->find();
		}

		if (!$invituser) {
			$invituser = M('User')->where(array('moble' => $invit))->find();
		}

		if ($invituser) {
			$invit_1 = $invituser['id'];
			$invit_2 = $invituser['invit_1'];
			$invit_3 = $invituser['invit_2'];
		}
		else {
			$invit_1 = 0;
			$invit_2 = 0;
			$invit_3 = 0;
		}

		for (; true; ) {
			$tradeno = tradenoa();

			if (!M('User')->where(array('invit' => $tradeno))->find()) {
				break;
			}
		}

		$mo = M();
		$mo->execute('set autocommit=0');
		$mo->execute('lock tables qqbtc_user write , qqbtc_user_coin write ');
		$rs = array();
		$rs[] = $mo->table('qqbtc_user')->add(array('username' => $username, 'moble' => $moble, 'mobletime' => time(), 'password' => md5($password), 'invit' => $tradeno, 'tpwdsetting' => 1, 'invit_1' => $invit_1, 'invit_2' => $invit_2, 'invit_3' => $invit_3, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'addtime' => time(), 'status' => 1));
		$rs[] = $mo->table('qqbtc_user_coin')->add(array('userid' => $rs[0]));

		if (check_arr($rs)) {
			$mo->execute('commit');
			$mo->execute('unlock tables');
			session('reguserId', $rs[0]);
			$this->success('注册成功！');
		}
		else {
			$mo->execute('rollback');
			$this->error('注册失败！');
		}
	}
	
	public function real($moble, $verify)
	{
		// if (!userid()) {
			// redirect('/#login');
		// }

		if (!check_verify(strtoupper($verify))) {
			$this->error('图形验证码错误!');
		}

		if (!check($moble, 'moble')) {
			$this->error('手机号码格式错误！');
		}

		if (M('User')->where(array('moble' => $moble))->find()) {
			$this->error('手机号码已存在！');
		}

		$code = rand(111111, 999999);
		session('real_verify', $code);
		$content = '您正在注册本站会员，您的验证码是' . $code;

		if (send_moble($moble, $content)) {
			if(MOBILE_CODE ==0 ){
				$this->success('目前是演示模式,请输入'.$code);
			}else{
				$this->success('验证码已发送');
			}

		}
		else {
			$this->error('验证码发送失败,请重发');
		}
	}

	public function register2()
	{
		$this->display();
	}

	public function upregister2($paypassword, $repaypassword)
	{
		if (!check($paypassword, 'password')) {
			$this->error('交易密码格式错误！');
		}

		if ($paypassword != $repaypassword) {
			$this->error('确认密码错误！');
		}

		if (!session('reguserId')) {
			$this->error('非法访问！');
		}

		if (M('User')->where(array('id' => session('reguserId'), 'password' => md5($paypassword)))->find()) {
			$this->error('交易密码不能和登录密码一样！');
		}

		if (M('User')->where(array('id' => session('reguserId')))->save(array('paypassword' => md5($paypassword)))) {
			$this->success('成功！');
		}
		else {
			$this->error('失败！');
		}
	}
	
	public function upregister2new($paypassword, $repaypassword, $truename, $idcard)
	{
		if (!check($truename, 'truename')) {
			$this->error('真实姓名格式错误！');
		}

		if (!check($idcard, 'idcard')) {
			$this->error('身份证号格式错误！');
		}
		
		if (!check($paypassword, 'password')) {
			$this->error('交易密码格式错误！');
		}

		if ($paypassword != $repaypassword) {
			$this->error('确认密码错误！');
		}

		if (!session('reguserId')) {
			$this->error('非法访问！');
		}

		if (M('User')->where(array('id' => session('reguserId'), 'password' => md5($paypassword)))->find()) {
			$this->error('交易密码不能和登录密码一样！');
		}

		if (M('User')->where(array('id' => session('reguserId')))->save(array('paypassword' => md5($paypassword), 'truename' => $truename, 'idcard' => $idcard))) {
			$this->success('成功！');
		}
		else {
			$this->error('失败！');
		}
	}

	public function register3()
	{
		$this->display();
	}

	public function upregister3($truename, $idcard)
	{
		if (!check($truename, 'truename')) {
			$this->error('真实姓名格式错误！');
		}

		if (!check($idcard, 'idcard')) {
			$this->error('身份证号格式错误！');
		}

		if (!session('reguserId')) {
			$this->error('非法访问！');
		}

		if (M('User')->where(array('id' => session('reguserId')))->save(array('truename' => $truename, 'idcard' => $idcard))) {
			$this->success('成功！');
		}
		else {
			$this->error('失败！');
		}
	}

	public function register4()
	{
		$user = M('User')->where(array('id' => session('reguserId')))->find();
		session('userId', $user['id']);
		session('userName', $user['username']);
		$this->assign('user', $user);
		$this->display();
	}

	public function chkUser($username)
	{
		if (!check($username, 'username')) {
			$this->error('用户名格式错误！');
		}

		if (M('User')->where(array('username' => $username))->find()) {
			$this->error('用户名已存在');
		}

		$this->success('');
	}

	public function submit($username, $password, $verify = NULL)
	{
		if (C('login_verify')) {
			if (!check_verify(strtoupper($verify))) {
				$this->error('图形验证码错误!');
			}
		}

		if (check($username, 'email')) {
			$user = M('User')->where(array('email' => $username))->find();
			$remark = '通过邮箱登录';
		}

		if (!$user && check($username, 'moble')) {
			$user = M('User')->where(array('moble' => $username))->find();
			$remark = '通过手机号登录';
		}

		if (!$user) {
			$user = M('User')->where(array('username' => $username))->find();
			$remark = '通过用户名登录';
		}

		if (!$user) {
			$this->error('用户不存在！');
		}

		if (!check($password, 'password')) {
			$this->error('登录密码格式错误！');
		}

		if (md5($password) != $user['password']) {
			$this->error('登录密码错误！');
		}

		if ($user['status'] != 1) {
			$this->error('你的账号已冻结请联系管理员！');
		}

		$mo = M();
		$mo->execute('set autocommit=0');
		$mo->execute('lock tables qqbtc_user write , qqbtc_user_log write ');
		$rs = array();
		$rs[] = $mo->table('qqbtc_user')->where(array('id' => $user['id']))->setInc('logins', 1);
		$rs[] = $mo->table('qqbtc_user_log')->add(array('userid' => $user['id'], 'type' => '登录', 'remark' => $remark, 'addtime' => time(), 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'status' => 1));

		if (check_arr($rs)) {
			$mo->execute('commit');
			$mo->execute('unlock tables');

			if (!$user['invit']) {
				for (; true; ) {
					$tradeno = tradenoa();

					if (!M('User')->where(array('invit' => $tradeno))->find()) {
						break;
					}
				}

				M('User')->where(array('id' => $user['id']))->setField('invit', $tradeno);
			}

			session('userId', $user['id']);
			session('userName', $user['username']);

			if (!$user['paypassword']) {
				session('regpaypassword', $rs[0]);
				session('reguserId', $user['id']);
			}

			if (!$user['truename']) {
				session('regtruename', $rs[0]);
				session('reguserId', $user['id']);
			}

			$this->success('登录成功！');
		}
		else {
			$mo->execute('rollback');
			$this->error('登录失败！');
		}
	}

	public function loginout()
	{
		session(null);
		redirect('/');
	}

	public function findpwd()
	{
		
		if (IS_POST) {
			$input = I('post.');
			
			if (!check_verify(strtoupper($input['verify']))) {
				$this->error('图形验证码错误!');
			}

			if (!check($input['username'], 'username')) {
				$this->error('用户名格式错误！');
			}

			if (!check($input['moble'], 'moble')) {
				$this->error('手机号码格式错误！');
			}

			if (!check($input['moble_verify'], 'd')) {
				$this->error('短信验证码格式错误！');
			}
			//启用验证码并且验证码错误
			if ( MOBILE_CODE==1 and $input['moble_verify'] != session('findpwd_verify')) {
				$this->error('短信验证码错误！');
			}

			$user = M('User')->where(array('username' => $input['username']))->find();

			if (!$user) {
				$this->error('用户名不存在！');
			}

			if ($user['moble'] != $input['moble']) {
				$this->error('用户名或手机号码错误！');
			}

			if (!check($input['password'], 'password')) {
				$this->error('新登录密码格式错误！');
			}

			if ($input['password'] != $input['repassword']) {
				$this->error('确认密码错误！');
			}

			$mo = M();
			$mo->execute('set autocommit=0');
			$mo->execute('lock tables qqbtc_user write , qqbtc_user_log write ');
			//$rs = array();
			$rs = $mo->table('qqbtc_user')->where(array('id' => $user['id']))->save(array('password' => md5($input['password'])));
			
			if ($rs>=0) {
				$mo->execute('commit');
				$mo->execute('unlock tables');
				$this->success('修改成功');
			}
			else {
				$mo->execute('rollback');
				$this->error('修改失败');
			}
		}
		else {
			$this->display();
		}
	}

	public function findpaypwd()
	{
		if (IS_POST) {
			$input = I('post.');

			if (!check($input['username'], 'username')) {
				$this->error('用户名格式错误！');
			}

			if (!check($input['moble'], 'moble')) {
				$this->error('手机号码格式错误！');
			}

			if (!check($input['moble_verify'], 'd')) {
				$this->error('短信验证码格式错误！');
			}

			if ($input['moble_verify'] != session('findpaypwd_verify')) {
				$this->error('短信验证码错误！');
			}

			$user = M('User')->where(array('username' => $input['username']))->find();

			if (!$user) {
				$this->error('用户名不存在！');
			}

			if ($user['moble'] != $input['moble']) {
				$this->error('用户名或手机号码错误！');
			}

			if (!check($input['password'], 'password')) {
				$this->error('新交易密码格式错误！');
			}

			if ($input['password'] != $input['repassword']) {
				$this->error('确认密码错误！');
			}

			$mo = M();
			$mo->execute('set autocommit=0');
			$mo->execute('lock tables qqbtc_user write , qqbtc_user_log write ');
			$rs = array();
			$rs[] = $mo->table('qqbtc_user')->where(array('id' => $user['id']))->save(array('paypassword' => md5($input['password'])));

			if (check_arr($rs)) {
				$mo->execute('commit');
				$mo->execute('unlock tables');
				$this->success('修改成功');
			}
			else {
				$mo->execute('rollback');
				$this->error('修改失败' . $mo->table('qqbtc_user')->getLastSql());
			}
		}
		else {
			$this->display();
		}
	}
}

?>