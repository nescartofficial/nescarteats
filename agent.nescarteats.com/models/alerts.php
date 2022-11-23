<?php
class Alerts
{
    public static function error_message($message = null, $type = 'error'){
	    $message = null;
		if (Session::exists('success_display')) {
		    $res = Session::flash('success_display');
		    $message = "<div class='alert alert-success text-center'>
                <span>{$res}</span>
            </div>";
		}
		if (Session::exists('error_display')) {
		    $res = Session::flash('error_display');
		    $message = "<div class='alert alert-danger text-center'>
                <span>{$res}</span>
            </div>";
		}
		
		return $message;
	}
	
	public static function displayError()
	{
		$s = '';
		if (Session::exists('error')) {
			$value = '' . Session::flash('error');
			$type = 'error';
			$s = "<script> alertToast('{$value}', '{$type}'); </script>";
		}
		//$s = "<script> alertToast('".Session::get('success')."'); </script>";
		echo $s;
	}

	public static function displayErrorModal()
	{
		// print General error Messages on modal
		$err = '';
		if (Session::exists('error-modal')) {
			$value = '' . Session::flash('error-modal');
			$type = 'error';
			$err = "<script> alertToast('{$value}', '{$type}'); </script>";
		}
		echo $err;
	}
	// print success messages
	public static function displaySuccess()
	{
		$s = '';
		if (Session::exists('success')) {
			$value = '' . Session::flash('success');
			$type = 'success';
			$s = "<script> alertToast('{$value}', '{$type}'); </script>";
		}
		//$s = "<script> alertToast('".Session::get('success')."'); </script>";
		echo $s;
	}
	public static function displaySuccessModal()
	{
		// print General error Messages on modal
		$err = '';
		if (Session::exists('success-modal')) {
			$value = '' . Session::flash('success-modal');
			$type = 'error';
			$err = "<script> alertToast('{$value}', '{$type}'); </script>";
		}
		echo $err;
	}
}
