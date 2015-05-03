<?php

namespace Addons\Card\Controller;

use Addons\Card\Controller\BaseController;

class NoticeController extends BaseController {
	var $model;
	function _initialize() {
		$this->model = $this->getModel ( 'card_notice' );
		parent::_initialize ();
	}
	// 通用插件的列表模型
	public function lists() {
		$map ['token'] = get_token ();
		session ( 'common_condition', $map );
		
		parent::common_lists ( $this->model );
	}
	
	// 通用插件的编辑模型
	public function edit() {
		parent::common_edit ( $this->model );
	}
	
	// 通用插件的增加模型
	public function add() {
		parent::common_add ( $this->model );
	}
	
	// 通用插件的删除模型
	public function del() {
		parent::common_del ( $this->model );
	}
	function show() {
		$map ['token'] = get_token ();
		$list = M ( 'card_notice' )->where ( $map )->order ( 'id desc' )->select ();
		$this->assign ( 'list', $list );
		
		$this->display ();
	}
}
