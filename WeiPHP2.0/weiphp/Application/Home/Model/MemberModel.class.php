<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Model;

use Think\Model;
use User\Api\UserApi;

/**
 * 文档基础模型
 */
class MemberModel extends Model {
	
	/* 用户模型自动完成 */
	protected $_auto = array (
			array (
					'login',
					0,
					self::MODEL_INSERT 
			),
			array (
					'reg_ip',
					'get_client_ip',
					self::MODEL_INSERT,
					'function',
					1 
			),
			array (
					'reg_time',
					NOW_TIME,
					self::MODEL_INSERT 
			),
			array (
					'last_login_ip',
					0,
					self::MODEL_INSERT 
			),
			array (
					'last_login_time',
					0,
					self::MODEL_INSERT 
			),
			array (
					'update_time',
					NOW_TIME 
			),
			array (
					'status',
					1,
					self::MODEL_INSERT 
			) 
	);
	
	/**
	 * 登录指定用户
	 *
	 * @param integer $uid
	 *        	用户ID
	 * @return boolean ture-登录成功，false-登录失败
	 */
	public function login($uid) {
		/* 检测是否在当前应用注册 */
		$user = $this->field ( true )->find ( $uid );
		if (! $user) { // 未注册
			/* 在当前应用中注册用户 */
			$Api = new UserApi ();
			$info = $Api->info ( $uid );
			$user = $this->create ( array (
					'nickname' => $info [1],
					'status' => 1 
			) );
			$user ['uid'] = $uid;
			if (! $this->add ( $user )) {
				$this->error = '前台用户信息注册失败，请重试！';
				return false;
			}
		} elseif (1 != $user ['status']) {
			$this->error = '用户未激活或已禁用！'; // 应用级别禁用
			return false;
		}
		
		/* 登录用户 */
		$this->autoLogin ( $user );
		
		// 记录行为
		action_log ( 'user_login', 'member', $uid, $uid );
		
		return $user;
	}
	
	/**
	 * 注销当前用户
	 *
	 * @return void
	 */
	public function logout() {
		$token = get_token();
		session ( 'mid', null );
		session ( 'user_auth', null );
		session ( 'user_auth_sign', null );
		session ( 'token', null );
		session ( 'openid_'.$token, null );
		session ( 'is_follow_login', null );
	}
	
	/**
	 * 自动登录用户
	 *
	 * @param integer $user
	 *        	用户信息数组
	 */
	public function autoLogin($user) {
		/* 更新登录信息 */
		$data = array (
				'uid' => $user ['uid'],
				'login' => array (
						'exp',
						'`login`+1' 
				),
				'last_login_time' => NOW_TIME,
				'last_login_ip' => get_client_ip ( 1 ) 
		);
		$this->save ( $data );
		
		/* 记录登录SESSION和COOKIES */
		$auth = array (
				'uid' => $user ['uid'],
				'username' => get_username ( $user ['uid'] ),
				'last_login_time' => $data ['last_login_time'] 
		);
		
		session ( 'mid', $user ['uid'] );
		session ( 'user_auth', $auth );
		session ( 'user_auth_sign', data_auth_sign ( $auth ) );
	}
	/**
	 * 获取用户全部信息
	 */
	public function getMemberInfo($uid) {
		static $_memberInfo;
		if (isset ( $_memberInfo [$uid] )) {
			return $_memberInfo [$uid];
		}
		
		$_memberInfo [$uid] = $this->find ( $uid );
		$_memberInfo [$uid] ['is_root'] = is_administrator ( $uid );
		return $_memberInfo [$uid];
	}
}
