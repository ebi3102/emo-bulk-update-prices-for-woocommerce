<?php

namespace Tests\Unit;

use EMO_BUPW\EMO_BUPW_Notice_Template;
use PHPUnit\Framework\TestCase;

class Notice_Template_Test  extends TestCase
{
	/** @test */
	public function success_is_rendered() : void
	{
		$message = "Your changes have been applied successfully";
		$closeButton    = "<button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this warning</span></button>";
		$expectedTemplate = "<div class='notice notice-success settings-error is-dismissible >";
		$expectedTemplate .= "<p><strong><span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>
        <span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>";
		$expectedTemplate .= $message . "</span></strong></p>" . $closeButton . "</div>";

		$this->assertSame($expectedTemplate, EMO_BUPW_Notice_Template::success ($message));

	}

}