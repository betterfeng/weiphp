<?php

namespace Addons\Test;
use Common\Controller\Addon;

/**
 * 微测试插件
 * @author 凡星
 */

    class TestAddon extends Addon{

        public $info = array(
            'name'=>'Test',
            'title'=>'微测试',
            'description'=>'主要功能有问卷管理，题目录入管理，用户信息和得分汇总管理。',
            'status'=>1,
            'author'=>'凡星',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Test/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Test/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }