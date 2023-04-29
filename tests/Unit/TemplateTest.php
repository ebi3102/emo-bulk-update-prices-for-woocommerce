<?php

namespace Tests\Unit;

use EMO_BUPW\EMO_BUPW_Echo_Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
	private EMO_BUPW_Echo_Template $template;

	protected function setUp(): void
	{
		parent::setUp();

		$this->template = new EMO_BUPW_Echo_Template();
	}
	/** @test */
	public function it_echo_as_template(): void
	{

		$expected =  "Template is echoed";

		$this->assertSame($expected, $this->template->template());
	}

}