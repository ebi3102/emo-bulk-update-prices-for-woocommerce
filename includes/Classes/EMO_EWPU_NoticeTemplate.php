<?php
/**
 * @package EWPU
 * ========================
 * Notice Template
 * ========================
 * Text Domain: emo_ewpu
 */

class EMO_EWPU_NoticeTemplate
{
    private static $template = null;
    private static $massage = null;
    private static $noticeType = null;
    private static $is_dismissible = false;

    private function __construct(){}

	/**
	 * @return string|null
	 */
	private static function render_template()
    {
        if(self::$is_dismissible){
            $is_dismissible = 'is-dismissible';
            $closeButton = "<button type='button' class='notice-dismiss'><span class='screen-reader-text'>".
            __('Dismiss this warning', 'emo_ewpu')."</span></button>";
        }else{
            $is_dismissible = '';
            $closeButton = '';
        }
        self::$template = "<div class='notice notice-".self::$noticeType." settings-error ".$is_dismissible."'>";
        self::$template .= "<p><strong><span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>
        <span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>";
        self::$template .= self::$massage . "</span></strong></p>".$closeButton."</div>";
        return self::$template; 
    }

	/**
	 * Render Success Notice massage
	 * @param string $massage
	 * @param bool $is_dismissible
	 *
	 * @return string|null
	 */
	public static function success (string $massage, bool $is_dismissible = true)
    {
        self::$noticeType = 'success';
        self::$is_dismissible = $is_dismissible;
        self::$massage = $massage;
        return self::render_template();

    }

	/**
	 * Render Warning Notice massage
	 * @param string $massage
	 * @param bool $is_dismissible
	 *
	 * @return string|null
	 */
	public static function warning (string $massage, bool $is_dismissible = true)
    {
        self::$noticeType = 'warning';
        self::$is_dismissible = $is_dismissible;
        self::$massage = $massage;
        return self::render_template();

    }


}
