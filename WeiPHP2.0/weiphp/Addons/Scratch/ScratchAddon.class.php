<?php

namespace Addons\Scratch;
use Common\Controller\Addon;

/**
 * 刮刮卡插件
 * @author 凡星
 */

    class ScratchAddon extends Addon{

        public $info = array(
            'name'=>'Scratch',
            'title'=>'刮刮卡',
            'description'=>'刮刮卡',
            'status'=>1,
            'author'=>'凡星',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Scratch/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Scratch/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }