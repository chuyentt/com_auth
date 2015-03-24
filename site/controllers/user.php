<?php

/**
 * @version     1.0.0
 * @package     com_auth
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.vn
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * User controller class.
 */
class AuthControllerUser extends AuthController {
    /**
     * Đăng nhập.
     *
     */

    public function login() {
    	// Initialise variables.
    	$app = JFactory::getApplication();
    	$username = $app->input->getVar('username');
    	$password = $app->input->getVar('password');    
    	$remember= $app->input->getVar('remember', 'off');
    	if($remember == 'off')
    	   $remember = false;
    	if($remember == 'on')
    	   $remember = true;
    	$credentials = array('username' => $username, 'password' => $password);
    	$options = array('remember' => $remember);
    	
    	if (empty($password))
	{
		$response->status = JAuthentication::STATUS_FAILURE;
		$response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');
		header('HTTP/1.0 401 Unauthorized');
		header('Content-type: application/json');
		echo json_encode($response);
		JFactory::getApplication()->close();
	}
	
	$result = JFactory::getApplication()->login($credentials, $options);
	if($result)
	{
		$response->email = JFactory::getUser()->email;
		$response->status = JAuthentication::STATUS_SUCCESS;
        $response->token = JSession::getFormToken() . '=1';
		header('Content-type: application/json');
		header('Token:'.JSession::getFormToken());
		echo json_encode($response);
	}
	else
	{
		$response->status = JAuthentication::STATUS_FAILURE;
		$response->error_message = JText::_('JGLOBAL_AUTH_INCORRECT');
		header('HTTP/1.0 401 Unauthorized');
		header('Content-type: application/json');
		echo json_encode($response);
	}
	JFactory::getApplication()->close();
    }
    
    /**
     * Đăng xuất
     *
     */
    public function logout() {
    	$app = JFactory::getApplication();              
    	$user = JFactory::getUser();
    	$user_id = $user->get('id');
    	$response->status = $app->logout($user_id, array());
    	header('Content-type: application/json');
    	header('token:'.JSession::getFormToken());
    	echo json_encode($response);
    	JFactory::getApplication()->close();
    }
}